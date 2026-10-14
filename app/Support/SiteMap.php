<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\FooterLink;
use App\Models\Page;

/**
 * Every public page of the site, grouped. Used by the sitemap page and sitemap.xml
 * so both always list the same pages. The Rates, Company and
 * Notices groups follow the footer columns (Admin → Footer); cities follow Admin → Cities.
 */
class SiteMap
{
    // [group label => [[label, url], …]]
    public static function groups(): array
    {
        $footer = FooterLink::activeByColumnCached();
        $column = fn (string $key) => array_map(fn ($l) => [$l['label'], url($l['url'])], $footer[$key] ?? []);

        $cities = [];
        foreach (RateBook::cities() as $key => [$name]) {
            [$state, $city] = explode('/', $key);
            $cities[] = ['Gold rate in ' . $name, route('rates.city', ['state' => $state, 'city' => $city])];
        }

        $company = $column('company');
        array_unshift($company, ['Home', route('home')]);
        if (! collect($company)->contains(fn ($l) => $l[1] === route('contact'))) {
            $company[] = ['Contact us', route('contact')];
        }

        // The Updates page and its latest posts (Admin → Updates)
        $updates = [['All updates', route('updates')]];
        foreach (Blog::active()->ordered()->take(10)->get(['title', 'slug']) as $post) {
            $updates[] = [$post->title, route('updates.show', $post->slug)];
        }

        $groups = [
            FooterLink::heading('rates') => $column('rates'),
            'Cities' => $cities,
            'Updates' => $updates,
            FooterLink::heading('company') => $company,
            FooterLink::heading('notices') => $column('notices'),
        ];

        // Policy pages and other published pages (Admin → Pages), plus the privacy request form
        $legal = Page::where('is_published', true)->orderBy('title')->get(['title', 'slug'])
            ->map(fn ($p) => [$p->title, route('pages.show', ['page' => $p->slug])])->all();
        $legal[] = ['Data access, correction or deletion request', route('privacy.request')];
        $groups['Legal & privacy'] = $legal;

        return array_filter($groups);
    }

    // Every URL on this site for sitemap.xml: the grouped pages plus every published update
    public static function urls(): array
    {
        $base = rtrim(url('/'), '/');
        $urls = [];
        foreach (self::groups() as $links) {
            foreach ($links as [, $url]) {
                $urls[] = $url;
            }
        }
        foreach (Blog::active()->get(['slug']) as $blog) {
            $urls[] = route('updates.show', $blog->slug);
        }

        // External links an admin may add to the footer do not belong in this site's sitemap
        $urls = array_filter($urls, fn ($u) => str_starts_with($u, $base));

        return array_values(array_unique($urls));
    }
}
