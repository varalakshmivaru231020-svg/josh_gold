<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * A product category with its own page at /collections/{slug} (gold coins, gold bars, gold jewellery…),
 * managed in Admin → Collections. Bullion collections list prices by weight; jewellery collections show a gallery.
 */
class Collection extends Model
{
    public const CACHE_KEY = 'collections.active';

    public const TYPES  = ['bullion' => 'Bullion', 'jewellery' => 'Jewellery'];
    public const METALS = ['gold' => 'Gold', 'silver' => 'Silver'];
    public const FORMS  = ['coin' => 'Coin', 'bar' => 'Bar', 'biscuit' => 'Biscuit'];

    // Photo used when a collection has no image or its file is missing, by "metal-form" (bullion) or "metal-jewellery"
    public const FALLBACK_IMAGES = [
        'gold-coin'        => 'images/site/gold-coins-wide.webp',
        'gold-bar'         => 'images/site/gold-bars-2-wide.webp',
        'gold-biscuit'     => 'images/site/gold-bars-2-wide.webp',
        'silver-coin'      => 'images/site/silver-coins-wide.webp',
        'silver-bar'       => 'images/site/silver-bar-card.webp',
        'silver-biscuit'   => 'images/site/silver-bar-card.webp',
        'gold-jewellery'   => 'images/site/gold-necklace-wide.webp',
        'silver-jewellery' => 'images/site/silver-jewellery-wide.webp',
    ];

    // Jewellery rate panel purities when none are set
    public const DEFAULT_JEWELLERY_PURITIES = ['gold' => ['22K', '18K'], 'silver' => ['925', '999']];

    // Active collections for the current request (also kept in the app cache until a collection is saved or deleted)
    protected static ?EloquentCollection $activeCache = null;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'metal',
        'form',
        'tagline',
        'intro',
        'body',
        'image',
        'gallery',
        'weights',
        'purities',
        'show_on_home',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'gallery'      => 'array',
        'show_on_home' => 'boolean',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }

    public static function flushCache(): void
    {
        static::$activeCache = null;
        Cache::forget(self::CACHE_KEY);
    }

    public function isBullion(): bool
    {
        return $this->type === 'bullion';
    }

    // Public page address
    public function getUrlAttribute(): string
    {
        return route('collections.show', $this->slug);
    }

    // Small label above the title, e.g. "Gold · Bullion"
    public function getEyebrowAttribute(): string
    {
        return (self::METALS[$this->metal] ?? ucfirst((string) $this->metal)) . ' · ' . (self::TYPES[$this->type] ?? ucfirst((string) $this->type));
    }

    // Weights to price, from the comma-separated grams: [['grams' => 116.64, 'label' => '116.64 g', 'name' => '10 tola'], …]
    public function getWeightListAttribute(): array
    {
        return static::parseWeights($this->weights);
    }

    // Purities that can be priced for this metal, in the order entered: ['24K', '22K'] or ['999', '925']
    public function getPurityListAttribute(): array
    {
        return static::parsePurities($this->purities, (string) $this->metal);
    }

    // Main image URL; falls back to a stock photo for the metal and form when it is empty or its file is missing
    public function getImageUrlAttribute(): string
    {
        return static::imageUrl($this->image) ?? versioned_asset($this->fallbackImage());
    }

    // Gallery image URLs, skipping files that are missing
    public function getGalleryUrlsAttribute(): array
    {
        return array_values(array_filter(array_map(
            fn ($path) => is_string($path) ? static::imageUrl($path) : null,
            (array) ($this->gallery ?? [])
        )));
    }

    public function fallbackImage(): string
    {
        $key = $this->metal . '-' . ($this->isBullion() ? $this->form : 'jewellery');

        return self::FALLBACK_IMAGES[$key] ?? self::FALLBACK_IMAGES[$this->metal . '-jewellery'] ?? self::FALLBACK_IMAGES['gold-jewellery'];
    }

    /**
     * URL of a stored image path, or null when the file does not exist.
     * "images/…" is a file in public/ (shipped with the site); anything else is an upload on the public disk.
     */
    public static function imageUrl(?string $path): ?string
    {
        $path = ltrim(trim((string) $path), '/');
        if ($path === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        if (static::isPublicAsset($path)) {
            return is_file(public_path($path)) ? asset($path) : null;
        }

        return Storage::disk('public')->exists($path) ? versioned_asset('storage/' . $path) : null;
    }

    // True for paths of files shipped in public/ (not admin uploads)
    public static function isPublicAsset(?string $path): bool
    {
        return str_starts_with(ltrim((string) $path, '/'), 'images/');
    }

    /**
     * "1, 2, 116.64 (10 tola), 1000" => [['grams' => 1.0, 'label' => '1 g', 'name' => null], …].
     * A name in brackets is optional. Items that are not a positive number are skipped.
     */
    public static function parseWeights(?string $weights): array
    {
        $list = [];
        $seen = [];
        foreach (explode(',', (string) $weights) as $item) {
            if (! preg_match('/^\s*(\d+(?:\.\d+)?)\s*(?:g|gm|gms|grams?)?\s*(?:\(([^()]*)\))?\s*$/i', $item, $m)) {
                continue;
            }
            $grams = (float) $m[1];
            if ($grams <= 0 || isset($seen[(string) $grams])) {
                continue;
            }
            $seen[(string) $grams] = true;
            $name = trim($m[2] ?? '');
            $list[] = ['grams' => $grams, 'label' => static::weightLabel($grams), 'name' => $name !== '' ? $name : null];
        }

        return $list;
    }

    // Items of a weights string that parseWeights() cannot read (for admin validation)
    public static function invalidWeights(?string $weights): array
    {
        $bad = [];
        foreach (explode(',', (string) $weights) as $item) {
            if (trim($item) === '') {
                continue;
            }
            if (static::parseWeights($item) === []) {
                $bad[] = trim($item);
            }
        }

        return $bad;
    }

    // 1000 => "1 kg", 116.64 => "116.64 g", 5 => "5 g"
    public static function weightLabel(float $grams): string
    {
        $trim = fn (float $n, int $dp) => rtrim(rtrim(number_format($n, $dp, '.', ''), '0'), '.');

        return $grams >= 1000 ? $trim($grams / 1000, 3) . ' kg' : $trim($grams, 2) . ' g';
    }

    // Purities a metal can be priced at (App\Models\MetalRate): gold 24K…10K, silver 999/925/900/800
    public static function purityOptions(string $metal): array
    {
        // Strings: PHP stores the numeric silver keys ('999') as integers
        return array_map('strval', array_keys($metal === 'silver' ? MetalRate::SILVER_PURITY : MetalRate::PURITY));
    }

    // "24k, 22K" => ['24K', '22K'], keeping only purities the metal can be priced at
    public static function parsePurities(?string $purities, string $metal): array
    {
        $allowed = static::purityOptions($metal);
        $list = [];
        foreach (explode(',', (string) $purities) as $item) {
            $item = strtoupper(str_replace(' ', '', $item));
            if (in_array($item, $allowed, true) && ! in_array($item, $list, true)) {
                $list[] = $item;
            }
        }

        return $list;
    }

    // Short note for a purity, e.g. 22K => "916", 925 => "Sterling"
    public static function purityNote(string $metal, string $purity): string
    {
        if ($metal === 'silver') {
            return trim(str_replace($purity, '', MetalRate::SILVER_PURITY[$purity]['label'] ?? ''));
        }

        $fine = ['24K' => '999', '22K' => '916', '20K' => '833', '18K' => '750', '16K' => '666', '14K' => '585', '12K' => '500', '10K' => '417'];

        return $fine[$purity] ?? '';
    }

    /**
     * Active collections in admin order (hydrated models without a query).
     * Cached forever; saving or deleting a collection clears it.
     */
    public static function activeCached(): EloquentCollection
    {
        if (static::$activeCache !== null) {
            return static::$activeCache;
        }

        try {
            $rows = Cache::rememberForever(self::CACHE_KEY, fn () => static::active()
                ->ordered()
                ->get()
                ->map(fn (Collection $c) => $c->getAttributes())
                ->all());

            return static::$activeCache = static::hydrate($rows);
        } catch (QueryException $e) {
            // The collections table does not exist yet (code deployed before "php artisan migrate"):
            // show no collections rather than breaking the page. Nothing is cached.
            report($e);

            return new EloquentCollection();
        }
    }

    // Active collections marked "Show on home page", in admin order (cached, see activeCached())
    public static function forHome(): EloquentCollection
    {
        return static::activeCached()->filter(fn (Collection $c) => $c->show_on_home)->values();
    }
}
