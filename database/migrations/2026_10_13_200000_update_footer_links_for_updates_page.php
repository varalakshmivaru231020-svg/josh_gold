<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Knowledge and Videos pages were removed and News became Updates (/updates):
     * drop the footer links to the removed pages and point the news link at /updates.
     * Data only; the footer and mobile drawer read this table.
     */
    public function up(): void
    {
        if (! Schema::hasTable('footer_links')) {
            return;
        }

        DB::table('footer_links')
            ->whereIn('url', ['/knowledge', '/knowledge/', 'knowledge', '/videos', '/videos/', 'videos', '/testimonials', '/testimonials/'])
            ->delete();

        DB::table('footer_links')
            ->whereIn('url', ['/news', '/news/', 'news', '/blog', '/blog/'])
            ->update(['label' => 'Updates', 'url' => '/updates', 'updated_at' => now()]);

        $this->flushFooterCache();
    }

    public function down(): void
    {
        if (! Schema::hasTable('footer_links')) {
            return;
        }

        DB::table('footer_links')
            ->where('url', '/updates')
            ->update(['label' => 'News & updates', 'url' => '/news', 'updated_at' => now()]);

        if (! DB::table('footer_links')->where('url', '/knowledge')->exists()) {
            DB::table('footer_links')->insert([
                'column_key'      => 'rates',
                'label'           => 'Knowledge',
                'url'             => '/knowledge',
                'open_in_new_tab' => false,
                'sort_order'      => (int) DB::table('footer_links')->where('column_key', 'rates')->max('sort_order') + 1,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        $this->flushFooterCache();
    }

    // Footer links are cached forever (App\Models\FooterLink::CACHE_KEY)
    private function flushFooterCache(): void
    {
        try {
            Cache::forget('footer_links.active');
        } catch (\Throwable $e) {
            // A cache store that is not reachable during deploys must not fail the migration
        }
    }
};
