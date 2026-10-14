<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// The Google map (Contact page) and TradingView charts (Charts page) now load with the page,
// and the cookie banner is gone (nothing optional is left to ask about). The Cookie and Privacy
// policies are updated to say so. Each replacement only applies if the original text is still there.
return new class extends Migration
{
    private function edits(): array
    {
        return [
            'cookie-policy' => [
                '<p class="policy-updated">Last updated: 13 October 2026</p>'
                    => '<p class="policy-updated">Last updated: 14 October 2026</p>',
                'In short: we use only the cookies the website needs to work, and content from Google Maps and TradingView loads only if you allow it.'
                    => 'In short: we use only the cookies the website needs to work. The map on our Contact page and the price charts on our Charts page come from Google and TradingView, load with the page, and may set those companies\' own cookies.',
                'They let the site remember things between pages or visits, for example that you have already made your cookie choice.'
                    => 'They let the site remember things between pages or visits, for example to keep a form working while you fill it in.',
                'They are always on and cannot be switched off in our cookie settings.'
                    => 'They are always on.',
                "\n" . '<tr><td data-label="Name"><code>josha_consent</code></td><td data-label="Provider">This website</td><td data-label="Purpose">Remembers your cookie choice, so we do not ask you again on every page</td><td data-label="Type">Strictly necessary</td><td data-label="Duration">12 months</td></tr>'
                    => '',
                '<h2 id="embedded-content">3. Embedded content (off until you allow it)</h2>'
                    => '<h2 id="embedded-content">3. Embedded content</h2>',
                'Some pages can show content from other companies. Until you allow <strong>Embedded content</strong> in our cookie settings, or click the button to load an item, we show a placeholder and nothing is loaded from these companies.'
                    => 'Two pages show content from other companies. It loads together with the page:',
                '<li><strong>Cookie settings:</strong> use the <a href="#your-choices" data-consent-open>Cookie settings</a> link at the bottom of every page to allow or block embedded content at any time.</li>' . "\n"
                    . '<li><strong>If you withdraw your consent:</strong> embedded content will not load again. Cookies that Google or TradingView have already set stay in your browser until they expire or you delete them in your browser settings.</li>'
                    => '<li><strong>Third-party cookies:</strong> to stop Google and TradingView setting cookies, block third-party cookies in your browser settings. They still receive your IP address and browser details when the map or a chart loads. Cookies they have already set stay in your browser until they expire or you delete them.</li>',
            ],
            'privacy-policy' => [
                '<p class="policy-updated">Last updated: 13 October 2026</p>'
                    => '<p class="policy-updated">Last updated: 14 October 2026</p>',
                'A few small cookies that keep forms working and remember your cookie choice</td>'
                    => 'A few small cookies that keep forms working</td>',
                '<td data-label="When">Only if you allow embedded content, or click to load the map on the Contact page</td>'
                    => '<td data-label="When">When you open the Contact page, where the map loads with the page</td>',
                '<td data-label="When">Only if you allow embedded content, or click to load a chart on the Charts page</td>'
                    => '<td data-label="When">When you open the Charts page, where the charts load with the page</td>',
                'We use only the cookies the website needs to work and to remember your cookie choice. Google Maps and TradingView content loads only if you allow it. We do not use analytics or advertising cookies. Full details are in our <a href="/cookie-policy">Cookie Policy</a>, and you can change your choice at any time with the <a href="/cookie-policy#your-choices" data-consent-open>Cookie settings</a> link at the bottom of every page.'
                    => 'We use only the cookies the website needs to work. The Google map on our Contact page and the TradingView charts on our Charts page load with the page, and those companies may set their own cookies. We do not use analytics or advertising cookies. Full details, including how to block third-party cookies, are in our <a href="/cookie-policy">Cookie Policy</a>.',
            ],
        ];
    }

    public function up(): void
    {
        $this->apply(false);
    }

    public function down(): void
    {
        $this->apply(true);
    }

    private function apply(bool $reverse): void
    {
        foreach ($this->edits() as $slug => $pairs) {
            $content = DB::table('pages')->where('slug', $slug)->value('content');
            if ($content === null) {
                continue;
            }
            $content = str_replace("\r\n", "\n", $content);
            foreach ($pairs as $from => $to) {
                [$a, $b] = $reverse ? [$to, $from] : [$from, $to];
                if ($a !== '' && str_contains($content, $a)) {
                    $content = str_replace($a, $b, $content);
                }
            }
            DB::table('pages')->where('slug', $slug)->update(['content' => $content, 'updated_at' => now()]);
        }
    }
};
