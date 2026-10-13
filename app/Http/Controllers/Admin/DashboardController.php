<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\GalleryPhoto;
use App\Models\MetalRate;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Support\RateBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    // A published rate older than this is flagged as stale on the dashboard
    private const STALE_AFTER_HOURS = 36;

    public function index()
    {
        $now   = Carbon::now(MetalRate::TIMEZONE);
        $rate  = $this->rateStatus($now);
        $trend = $this->goldTrend($rate['latest']);

        $monthStart = $now->copy()->startOfMonth()->setTimezone(config('app.timezone'));

        $enquiries = [
            'unread'     => ContactMessage::unread()->count(),
            'this_month' => ContactMessage::where('created_at', '>=', $monthStart)->count(),
            'total'      => ContactMessage::count(),
            'recent'     => ContactMessage::latest()->take(5)->get(),
        ];

        $kpis = $this->kpis($enquiries);

        $recentRates = MetalRate::orderByDesc('rate_date')
            ->orderByRaw("FIELD(session, 'evening', 'morning')")
            ->take(6)
            ->get();

        $sampleCount = MetalRate::where('is_sample', true)->count();

        $contactLabels  = [
            'contact_phone'    => 'Phone',
            'contact_email'    => 'Email',
            'contact_whatsapp' => 'WhatsApp',
            'contact_address'  => 'Address',
        ];
        $missingContact = [];
        foreach ($contactLabels as $key => $label) {
            if (trim((string) SiteSetting::get($key)) === '') {
                $missingContact[] = $label;
            }
        }

        $quickActions = $this->quickActions();

        return view('admin.dashboard', compact(
            'now', 'rate', 'trend', 'enquiries', 'kpis', 'recentRates', 'sampleCount', 'missingContact', 'quickActions'
        ));
    }

    // Latest published rate, its age, the previous close and which of today's sessions are in
    private function rateStatus(Carbon $now): array
    {
        $latest = MetalRate::currentRate();

        $status = [
            'latest'        => $latest,
            'gold'          => null,
            'silver'        => null,
            'published'     => null,
            'age_hours'     => null,
            'stale'         => true,
            'previous'      => null,
            'gold_change'   => null,
            'silver_change' => null,
            'today'         => MetalRate::where('rate_date', $now->toDateString())->pluck('session')->all(),
        ];

        if (! $latest) {
            return $status;
        }

        $published = $latest->published_at;
        $ageHours  = max(0, ($now->getTimestamp() - $published->getTimestamp()) / 3600);

        // Previous day's closing rate (the day before the latest rate's date)
        $previous = RateBook::closeOnOrBefore($latest->rate_date->copy()->subDay());

        $gold   = $latest->goldRate('24K', 10);
        $silver = $latest->silverRate(1000);

        return array_merge($status, [
            'gold'          => $gold,
            'silver'        => $silver,
            'published'     => $published,
            'age_hours'     => $ageHours,
            'stale'         => $ageHours > self::STALE_AFTER_HOURS,
            'previous'      => $previous,
            'gold_change'   => $previous ? $this->change($gold, $previous->goldRate('24K', 10)) : null,
            'silver_change' => $previous ? $this->change($silver, $previous->silverRate(1000)) : null,
        ]);
    }

    private function change(float $now, float $before): ?array
    {
        if ($before == 0.0) {
            return null;
        }

        return ['diff' => $now - $before, 'pct' => ($now - $before) / $before * 100];
    }

    // 24K gold per 10 g, one closing point per day for the last 30 days that have a rate, oldest first
    private function goldTrend(?MetalRate $latest): array
    {
        if (! $latest) {
            return ['labels' => [], 'values' => [], 'has_sample' => false, 'high' => null, 'low' => null, 'change' => null];
        }

        $closes = RateBook::dailyCloses($latest, 30)->reverse()->values();
        $values = $closes->map(fn (MetalRate $r) => $r->goldRate('24K', 10))->all();

        return [
            'labels'     => $closes->map(fn (MetalRate $r) => $r->rate_date->format('d M'))->all(),
            'dates'      => $closes->map(fn (MetalRate $r) => $r->rate_date->format('D, d M Y') . ' · ' . ucfirst($r->session))->all(),
            'values'     => $values,
            'has_sample' => $closes->contains(fn (MetalRate $r) => $r->is_sample),
            'high'       => $values ? max($values) : null,
            'low'        => $values ? min($values) : null,
            'change'     => count($values) > 1 ? $this->change(end($values), $values[0]) : null,
            'days'       => count($values),
        ];
    }

    // Content counts for the KPI tiles; modules whose table does not exist yet are left out
    private function kpis(array $enquiries): array
    {
        $tiles = [
            [
                'label' => 'Unread enquiries',
                'value' => $enquiries['unread'],
                'note'  => $enquiries['total'] . ' in total',
                'icon'  => 'fa-envelope',
                'route' => 'admin.site-content.contact-messages',
                'alert' => $enquiries['unread'] > 0,
            ],
            [
                'label' => 'Enquiries this month',
                'value' => $enquiries['this_month'],
                'note'  => 'Since 1 ' . Carbon::now(MetalRate::TIMEZONE)->format('M'),
                'icon'  => 'fa-inbox',
                'route' => 'admin.site-content.contact-messages',
            ],
        ];

        $content = [
            ['Updates',        Blog::class,                'blogs',          'fa-newspaper',   'admin.site-content.blogs'],
            // Collections (category pages) is counted once its table exists
            ['Collections',    'App\\Models\\Collection',  'collections',    'fa-layer-group', 'admin.collections.index'],
            ['Gallery photos', GalleryPhoto::class,        'gallery_photos', 'fa-images',      'admin.site-content.gallery'],
            ['Services',       Service::class,             'services',       'fa-gem',         'admin.site-content.services'],
            ['Cities',         'App\\Models\\City',        'cities',         'fa-city',        'admin.cities.index'],
        ];

        foreach ($content as [$label, $model, $table, $icon, $route]) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $query = class_exists($model) ? $model::query() : DB::table($table);
            $total = (clone $query)->count();
            $live  = Schema::hasColumn($table, 'is_active') ? (clone $query)->where('is_active', true)->count() : null;

            $tiles[] = [
                'label' => $label,
                'value' => $total,
                'note'  => $live !== null ? $live . ' live on site' : 'On the website',
                'icon'  => $icon,
                'route' => $route,
            ];
        }

        foreach ($tiles as &$tile) {
            $tile['url'] = Route::has($tile['route']) ? route($tile['route']) : null;
        }

        return $tiles;
    }

    private function quickActions(): array
    {
        $actions = [
            ['Add today\'s rate', 'Publish gold & silver', 'fa-plus',             'admin.metal-rates.create',           false],
            ['View enquiries',    'Contact form messages', 'fa-envelope-open',    'admin.site-content.contact-messages', false],
            ['Add update',        'Post news with a photo', 'fa-newspaper',       'admin.site-content.blogs.create',    false],
            ['Edit footer',       'Links & details',       'fa-table-columns',    'admin.footer.index',                 false],
            ['View website',      'Opens in a new tab',    'fa-up-right-from-square', 'home',                           true],
        ];

        $out = [];
        foreach ($actions as [$label, $hint, $icon, $route, $external]) {
            if (Route::has($route)) {
                $out[] = compact('label', 'hint', 'icon', 'external') + ['url' => route($route)];
            }
        }

        return $out;
    }
}
