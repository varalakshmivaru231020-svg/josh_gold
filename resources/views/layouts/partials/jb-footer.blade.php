{{--
    Site footer, floating WhatsApp button and the mobile-drawer script, used by layouts/bullion and layouts/lawfirm.
    Expects from the view composer: $socialLinks, $footerOffices, $footerPages
--}}
@php
    use App\Models\SiteSetting;

    $jfPhone    = trim((string) SiteSetting::get('contact_phone'));
    $jfEmail    = trim((string) SiteSetting::get('contact_email'));
    $jfWhatsapp = preg_replace('/\D+/', '', (string) SiteSetting::get('contact_whatsapp')) ?: '919632527961';
    $jfHours    = trim((string) SiteSetting::get('contact_working_hours'));
    $jfAddress  = trim((string) SiteSetting::get('contact_address'))
        ?: (string) $footerOffices->first(fn ($o) => filled($o->address))?->address;
    $jfNotices = [
        ['Central Anti-Conversion Law',               route('anti-conversion-law')],
        ['SC/ST (Prevention of Atrocities) Act, 1989', route('sc-st-act-1989')],
        ['Letter to PM Modi',                         route('letter-to-pm-modi')],
        ['Letter to CM Karnataka',                    route('letter-to-cm-karnataka')],
        ['Water Filtration Project',                  route('water-filtration')],
        ['Water Filtration (Kannada)',                route('water-filtration-kannada')],
    ];
@endphp

<footer class="jb-footer">
    <div class="jb-wrap">
        <div class="jb-footer__grid">
            <div>
                <a href="{{ route('home') }}" class="jb-footer__logo">
                    <img src="{{ asset('images/brand/josha-logo.webp') }}" alt="{{ config('company.name') }}" width="200" height="165" loading="lazy">
                </a>
                <p class="jb-footer__about">Daily gold and silver rates, published each morning and evening, with clear pricing by purity and weight.</p>
                @if($socialLinks->isNotEmpty())
                    <div class="jb-footer__social">
                        @foreach($socialLinks as $link)
                            <a href="{{ $link->url }}" target="_blank" rel="noopener" aria-label="{{ $link->platform }}" title="{{ $link->platform }}">
                                @if($link->icon)<i class="{{ $link->icon }}" aria-hidden="true"></i>@else{{ \Illuminate\Support\Str::substr($link->platform, 0, 1) }}@endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h4>Rates</h4>
                <ul>
                    <li><a href="{{ route('rates.gold') }}">Gold rate today</a></li>
                    <li><a href="{{ route('rates.silver') }}">Silver rate today</a></li>
                    <li><a href="{{ route('rates.cities') }}">Rates by city</a></li>
                    <li><a href="{{ route('rates.charts') }}">Charts</a></li>
                    <li><a href="{{ route('rates.pulse') }}">Gold Pulse</a></li>
                    <li><a href="{{ route('live-rates') }}">Full rate table</a></li>
                    <li><a href="{{ route('knowledge') }}">Knowledge</a></li>
                </ul>
            </div>

            <div>
                <h4>Company</h4>
                <ul>
                    <li><a href="{{ route('about-us') }}">About us</a></li>
                    <li><a href="{{ route('services.index') }}">Services</a></li>
                    <li><a href="{{ route('offices.index') }}">Our offices</a></li>
                    <li><a href="{{ route('gallery') }}">Gallery</a></li>
                    <li><a href="{{ route('news') }}">News &amp; updates</a></li>
                    <li><a href="{{ route('bank-details') }}">Bank details</a></li>
                </ul>
            </div>

            <div>
                <h4>Notices &amp; letters</h4>
                <ul>
                    @foreach($jfNotices as [$label, $url])
                        <li><a href="{{ $url }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4>Contact</h4>
                <ul>
                    @if($jfPhone !== '')<li><a href="tel:{{ preg_replace('/[^\d+]/', '', $jfPhone) }}">{{ $jfPhone }}</a></li>@endif
                    @if($jfEmail !== '')<li><a href="mailto:{{ $jfEmail }}" style="word-break: break-all;">{{ $jfEmail }}</a></li>@endif
                    <li><a href="https://wa.me/{{ $jfWhatsapp }}" target="_blank" rel="noopener">WhatsApp us</a></li>
                    @if($jfHours !== '')<li>{{ $jfHours }}</li>@endif
                    @if($jfAddress !== '')<li>{!! nl2br(e($jfAddress)) !!}</li>@endif
                    <li><a href="{{ route('contact') }}">Contact page →</a></li>
                </ul>
            </div>
        </div>
    </div>
    <hr class="jb-footer__rule">
    <div class="jb-wrap">
        <div class="jb-footer__bottom">
            <span>&copy; {{ date('Y') }} {{ config('company.name') }}. All rights reserved. · GSTIN {{ config('company.gstin') }}</span>
            @if($footerPages->isNotEmpty())
                <nav class="jb-footer__legal" aria-label="Legal and information pages">
                    @foreach($footerPages as $footerPage)
                        <a href="{{ route('pages.show', ['page' => $footerPage->slug]) }}">{{ $footerPage->title }}</a>
                    @endforeach
                </nav>
            @endif
        </div>
        <p class="jb-footer__note">Rates on this site are reference prices for the metal value only, based on our published morning and evening rates. Making charges and GST are extra. Please confirm the final price with our desk before you pay.</p>
    </div>
</footer>

<a href="https://wa.me/{{ $jfWhatsapp }}" target="_blank" rel="noopener" class="jb-wa-float" aria-label="Chat on WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.91-4.45 9.91-9.91A9.85 9.85 0 0 0 12.04 2Zm5.8 14.06c-.24.68-1.42 1.31-1.96 1.36-.5.05-.97.23-3.27-.68-2.77-1.09-4.52-3.92-4.66-4.1-.13-.18-1.11-1.48-1.11-2.83s.7-2 .96-2.28c.25-.27.55-.34.73-.34l.53.01c.17 0 .4-.06.62.48.24.56.8 1.94.87 2.08.07.14.11.3.02.48-.09.18-.14.3-.27.46l-.41.48c-.14.14-.28.29-.12.56.16.27.71 1.17 1.52 1.9 1.05.93 1.93 1.22 2.2 1.36.27.14.43.11.59-.07.16-.18.68-.79.86-1.06.18-.27.36-.23.61-.14.25.09 1.6.75 1.87.89.27.14.46.2.52.32.07.11.07.66-.17 1.33Z"/></svg>
</a>

<script>
    (function () {
        var drawer = document.getElementById('jbDrawer');
        var openBtn = document.getElementById('jbMenuOpen');
        if (!drawer || !openBtn) return;

        function setOpen(open) {
            drawer.classList.toggle('is-open', open);
            drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
            openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.classList.toggle('jb-locked', open);
            (open ? drawer.querySelector('.jb-menu-btn[data-drawer-close]') : openBtn).focus();
        }

        openBtn.addEventListener('click', function () { setOpen(true); });
        drawer.querySelectorAll('[data-drawer-close]').forEach(function (el) {
            el.addEventListener('click', function () { setOpen(false); });
        });
        // Links to a section of the current page close the drawer
        drawer.querySelectorAll('a[href*="#"]').forEach(function (a) {
            a.addEventListener('click', function () { setOpen(false); });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && drawer.classList.contains('is-open')) setOpen(false);
        });
    })();
</script>
