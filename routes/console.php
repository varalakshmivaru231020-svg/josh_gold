<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Save the live spot prices once a minute for the live charts.
// Server cron (once): * * * * * cd /path/to/site && php artisan schedule:run >> /dev/null 2>&1
Artisan::command('spot:record', function () {
    $p = \App\Support\SpotPrices::fetch();
    if (! $p) {
        $this->error('Live prices are unavailable right now.');
        return 1;
    }
    \Illuminate\Support\Facades\Cache::put('spot.prices.last', $p);
    \App\Support\SpotPrices::record($p);
    $pruned = \App\Support\SpotPrices::prune();
    $this->info('Saved: gold $' . $p['xau_usd'] . ', silver $' . $p['xag_usd'] . ', USD/INR ' . $p['usd_inr'] . ($pruned ? " (removed $pruned old rows)" : ''));
})->purpose('Save the live spot prices for the charts');

\Illuminate\Support\Facades\Schedule::command('spot:record')->everyMinute()->withoutOverlapping();

// Sample chart history (flagged, shown with a "Sample data" tag; removed automatically after 24 h of real prices)
Artisan::command('spot:sample {--clear : Remove the sample rows}', function () {
    if ($this->option('clear')) {
        $this->info('Removed ' . \App\Support\SpotPrices::clearSample() . ' sample rows. Real prices were not touched.');
        return;
    }
    $n = \App\Support\SpotPrices::addSample();
    $this->info($n ? "Added $n sample rows (the 24 hours before the first real price). They are removed automatically after 24 hours of real prices, or with: php artisan spot:sample --clear"
                   : 'Could not add sample rows: no live price available.');
})->purpose('Add (or --clear) flagged sample history for the live charts');
