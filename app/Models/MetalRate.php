<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MetalRate extends Model
{
    protected $fillable = [
        'rate_date',
        'session',
        'gold_999_rate',
        'silver_999_rate',
        'remarks',
        'is_sample',
    ];

    protected $casts = [
        'rate_date'       => 'date',
        'gold_999_rate'   => 'float',
        'silver_999_rate' => 'float',
        'is_sample'       => 'boolean',
    ];

    // -------------------------------------------------------
    // Gold purity multipliers (relative to 999 purity = 1)
    // -------------------------------------------------------
    const PURITY = [
        '24K' => 1.0,        // 999/999
        '22K' => 22 / 24,    // 916/999 approx
        '20K' => 20 / 24,
        '18K' => 18 / 24,    // 750/999 approx
        '16K' => 16 / 24,
        '14K' => 14 / 24,
        '12K' => 12 / 24,
        '10K' => 10 / 24,
    ];

    // Silver purity code => display name + multiplier (relative to 999 = 1)
    const SILVER_PURITY = [
        '999' => ['name' => 'Silver 999 Fine',     'label' => '999 Fine',     'multiplier' => 1.0],
        '925' => ['name' => 'Silver 925 Sterling', 'label' => '925 Sterling', 'multiplier' => 0.925],
        '900' => ['name' => 'Silver 900 Coin',     'label' => '900 Coin',     'multiplier' => 0.900],
        '800' => ['name' => 'Silver 800 German',   'label' => '800 German',   'multiplier' => 0.800],
    ];

    // Price-unit code => grams (rates are stored per 1 gm)
    const UNIT_GRAMS = [
        '1gm'   => 1,
        '10gm'  => 10,
        '100gm' => 100,
        '1kg'   => 1000,
    ];

    // Rates are published for a session, not at an exact minute. When a rate was not
    // saved on its own date (e.g. back-filled history) we place it at its session's
    // usual time, in Indian Standard Time.
    const TIMEZONE = 'Asia/Kolkata';
    const SESSION_TIMES = [
        'morning' => '10:00',
        'evening' => '17:00',
    ];

    // -------------------------------------------------------
    // Compute gold rate for a given purity and weight
    // gold_999_rate is stored as per 10 gm
    // -------------------------------------------------------
    // gold_999_rate is stored as per 1 gm
    public function goldRate(string $purity, float $grams): float
    {
        $per1gm = $this->gold_999_rate * (self::PURITY[$purity] ?? 1.0);
        return round($per1gm * $grams, 2);
    }

    // -------------------------------------------------------
    // Compute silver rate for a given weight
    // silver_999_rate is stored as per 1 kg (1000 gm)
    // -------------------------------------------------------
    // silver_999_rate is stored as per 1 gm
    public function silverRate(float $grams): float
    {
        return round($this->silver_999_rate * $grams, 2);
    }

    // -------------------------------------------------------
    // Price of $grams of the given metal/purity, e.g. ('gold', '22K', 10)
    // -------------------------------------------------------
    public function priceFor(string $metal, string $purity, float $grams): float
    {
        if ($metal === 'gold') {
            return $this->goldRate($purity, $grams);
        }

        $multiplier = self::SILVER_PURITY[$purity]['multiplier'] ?? 1.0;
        return round($this->silver_999_rate * $multiplier * $grams, 2);
    }

    // -------------------------------------------------------
    // The moment this rate went live (Asia/Kolkata): the time it was saved in
    // admin when that was on its own date, otherwise its session's usual time.
    // -------------------------------------------------------
    public function getPublishedAtAttribute(): Carbon
    {
        $date  = $this->rate_date->toDateString();
        $saved = $this->created_at?->copy()->setTimezone(self::TIMEZONE);

        if ($saved && $saved->toDateString() === $date) {
            return $saved;
        }

        $time = self::SESSION_TIMES[$this->session] ?? '12:00';
        return Carbon::parse("$date $time", self::TIMEZONE);
    }

    public function silverPurityTable(): array
    {
        $weights = [1, 10, 100, 1000, 'ounce' => 28.34952, 'tola' => 11.6638];
        $table   = [];
        foreach (self::SILVER_PURITY as $type) {
            $row = ['name' => $type['name'], 'weights' => []];
            foreach ($weights as $key => $grams) {
                $k = is_numeric($key) ? $grams : $key;
                $per1gm = $this->silver_999_rate * $type['multiplier'];
                $row['weights'][$k] = round($per1gm * $grams, 2);
            }
            $table[] = $row;
        }
        return $table;
    }

    // -------------------------------------------------------
    // Build the full auto-computed purity table for gold
    // Returns array indexed by purity key
    // -------------------------------------------------------
    public function goldPurityTable(): array
    {
        $purities = ['24K', '22K', '20K', '18K', '16K', '14K', '12K', '10K'];
        $weights  = [1, 10, 100, 1000, 'ounce' => 28.34952, 'tola' => 11.6638];
        $table    = [];
        foreach ($purities as $purity) {
            $row = ['purity' => $purity, 'weights' => []];
            foreach ($weights as $key => $grams) {
                // If numeric key, use grams as key, else use the string key
                $k = is_numeric($key) ? $grams : $key;
                $row['weights'][$k] = $this->goldRate($purity, $grams);
            }
            $table[] = $row;
        }
        return $table;
    }

    // -------------------------------------------------------
    // Fetch the session that immediately preceded this one
    // -------------------------------------------------------
    public function previousRate(): ?self
    {
        return self::where(function ($q) {
                $q->where('rate_date', $this->rate_date)
                  ->where('session', 'morning')
                  ->where('session', '!=', $this->session);
            })
            ->orWhere(function ($q) {
                $q->where('rate_date', '<', $this->rate_date);
            })
            ->orderByDesc('rate_date')
            ->orderByRaw("FIELD(session, 'evening', 'morning')")
            ->first();
    }

    // Simpler approach: get the last row before this one
    public function getPreviousRate(): ?self
    {
        return self::where(function ($q) {
            $q->where('rate_date', '<', $this->rate_date)
              ->orWhere(function ($inner) {
                  $inner->where('rate_date', $this->rate_date)
                        ->where('session', 'morning')
                        ->where('id', '<', $this->id);
              });
        })
        ->orderByDesc('rate_date')
        ->orderByRaw("CASE session WHEN 'evening' THEN 0 ELSE 1 END")
        ->first();
    }

    // -------------------------------------------------------
    // Scope: latest entry (for homepage ticker)
    // -------------------------------------------------------
    public function scopeLatest($query)
    {
        return $query->orderByDesc('rate_date')
                     ->orderByRaw("FIELD(session, 'evening', 'morning')");
    }

    // -------------------------------------------------------
    // Get the most recent rate entry
    // -------------------------------------------------------
    public static function currentRate(): ?self
    {
        return self::orderByDesc('rate_date')
                   ->orderByRaw("FIELD(session, 'evening', 'morning')")
                   ->first();
    }
}
