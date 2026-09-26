<?php

namespace App\Support;

use App\Models\SiteSetting;

/**
 * The "call to action" banner shown above the footer on public pages.
 * Stored as site settings (cta_*) and edited in Admin → Call To Action.
 *
 * A setting that was never saved uses its default; one the admin saved as blank stays
 * blank (and that part of the banner is simply not shown).
 */
class CtaContent
{
    public const DEFAULTS = [
        'cta_enabled'       => '1',
        'cta_heading'       => "Today's gold & silver rates, published twice daily",
        'cta_subtext'       => 'Morning and evening rates with price history, straight from Josha Infinity.',
        'cta_button1_label' => 'View Live Rates',
        'cta_button1_url'   => '',   // blank = the Live Rates page
        'cta_button2_label' => 'WhatsApp Us',
        'cta_button2_url'   => '',   // blank = a WhatsApp chat with the number in Contact Settings
    ];

    /** @return array<string, ?string> */
    public static function get(): array
    {
        $content = [];
        foreach (self::DEFAULTS as $key => $default) {
            $content[$key] = SiteSetting::get($key) ?? $default;
        }
        $content['cta_image'] = SiteSetting::get('cta_image') ?: null;

        return $content;
    }

    /**
     * The buttons to draw, with blank links resolved to their defaults.
     * A button without text, or without anywhere to go, is left out.
     *
     * @return array<int, array{n:int, label:string, url:string, icon:string, external:bool}>
     */
    public static function buttons(array $content): array
    {
        $whatsapp = preg_replace('/\D+/', '', (string) SiteSetting::get('contact_whatsapp'));
        $fallback = [
            1 => route('live-rates'),
            2 => $whatsapp !== '' ? "https://wa.me/$whatsapp" : '',
        ];

        $buttons = [];
        foreach ([1, 2] as $n) {
            $label = trim((string) ($content["cta_button{$n}_label"] ?? ''));
            $url   = trim((string) ($content["cta_button{$n}_url"] ?? '')) ?: $fallback[$n];

            if ($label === '' || $url === '') {
                continue;
            }

            $buttons[] = [
                'n'        => $n,
                'label'    => $label,
                'url'      => $url,
                'icon'     => self::iconFor($url),
                'external' => (bool) preg_match('#^https?://#i', $url) && ! str_starts_with($url, url('/')),
            ];
        }

        return $buttons;
    }

    private static function iconFor(string $url): string
    {
        return match (true) {
            str_starts_with($url, 'tel:')                    => 'fas fa-phone',
            str_starts_with($url, 'mailto:')                 => 'fas fa-envelope',
            (bool) preg_match('#wa\.me|whatsapp#i', $url)    => 'fab fa-whatsapp',
            str_contains($url, 'live-rates')                 => 'fas fa-chart-line',
            default                                          => '',
        };
    }
}
