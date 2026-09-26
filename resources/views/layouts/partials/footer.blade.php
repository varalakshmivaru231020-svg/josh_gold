{{--
    Global site footer. Everything except the quick links comes from admin and is optional:
    Offices, Contact Settings (phone, email, WhatsApp, hours) and Connect Content (social links).
    A field left blank is simply not shown.
    Expects (from the layout / view composer): $siteLogo, $logoUrl, $socialLinks, $footerOffices
--}}
@php
    use App\Models\SiteSetting;

    $phone    = trim((string) SiteSetting::get('contact_phone'));
    $email    = trim((string) SiteSetting::get('contact_email'));
    $whatsapp = preg_replace('/\D+/', '', (string) SiteSetting::get('contact_whatsapp'));
    $hours    = trim((string) SiteSetting::get('contact_working_hours'));

    $offices    = $footerOffices->filter(fn ($o) => filled($o->title) || filled($o->address));
    $hasOffices = $offices->isNotEmpty();
    $hasContact = $phone !== '' || $email !== '' || $whatsapp !== '' || $hours !== '';

    // Column widths adapt to which optional sections exist
    $brandCol = $hasOffices && $hasContact ? 'col-lg-4' : ($hasOffices || $hasContact ? 'col-lg-5' : 'col-lg-7');
    $linksCol = $hasOffices && $hasContact ? 'col-lg-2' : ($hasOffices || $hasContact ? 'col-lg-3' : 'col-lg-5');

    $quickLinks = [
        ['Home',            route('home')],
        ['Live Rates',      route('live-rates')],
        ['About Us',        route('about-us')],
        ['Services',        route('services.index')],
        ['Bank Details',    route('bank-details')],
        ['Gallery',         route('gallery')],
        ['News & Updates',  route('news')],
        ['Contact Us',      route('contact')],
    ];
@endphp

<style>
    .sf { --sf-display: "Cormorant Garamond", Georgia, serif; background: linear-gradient(180deg, #fffdf8 0%, #f7f0df 100%); color: #4a412c; border-top: 4px solid var(--gold); position: relative; overflow: hidden; font-size: .95rem; }
    .sf::before {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background-image: linear-gradient(rgba(201,168,76,.10) 1px, transparent 1px), linear-gradient(90deg, rgba(201,168,76,.10) 1px, transparent 1px);
        background-size: 56px 56px;
    }
    .sf > * { position: relative; }
    .sf a { color: inherit; text-decoration: none; }

    /* Columns */
    .sf-main { padding-top: 3.5rem; padding-bottom: 2.5rem; }
    .sf-brand { display: flex; align-items: center; gap: .9rem; margin-bottom: 1rem; }
    .sf-brand-mark { width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg, var(--gold), var(--gold-dark)); display: grid; place-items: center; color: #fff; font-size: 1.5rem; flex-shrink: 0; overflow: hidden; box-shadow: 0 6px 16px rgba(139,105,20,.25); }
    .sf-brand-mark img { width: 100%; height: 100%; object-fit: cover; }
    .sf-brand-name { font-family: var(--sf-display); color: #2b2417; font-size: 1.25rem; font-weight: 700; letter-spacing: 1px; line-height: 1.15; }
    .sf-brand-sub { font-size: .72rem; letter-spacing: .2em; text-transform: uppercase; color: var(--gold-dark); font-weight: 600; margin-top: .2rem; }
    .sf-about { max-width: 340px; line-height: 1.75; margin-bottom: 1.25rem; color: #6f6650; }
    .sf-social { display: flex; gap: .55rem; flex-wrap: wrap; }
    .sf-social a { width: 38px; height: 38px; border-radius: 50%; border: 1px solid rgba(139,105,20,.4); display: grid; place-items: center; color: var(--gold-dark); transition: background .2s, color .2s, border-color .2s, transform .2s; }
    .sf-social a:hover { background: var(--gold); border-color: var(--gold); color: #2b2417; transform: translateY(-2px); }

    .sf-title { font-family: var(--sf-display); color: #2b2417; font-size: 1.3rem; font-weight: 700; margin: 0 0 1.25rem; padding-bottom: .65rem; position: relative; }
    .sf-title::after { content: ''; position: absolute; left: 0; bottom: 0; width: 34px; height: 2px; background: var(--gold); }

    .sf-links { list-style: none; margin: 0; padding: 0; }
    .sf-links a { display: inline-flex; align-items: center; gap: .55rem; padding: .3rem 0; transition: color .2s, transform .2s; }
    .sf-links a::before { content: '\203A'; color: var(--gold-dark); font-size: 1.1rem; line-height: 1; }
    .sf-links a:hover { color: var(--gold-dark); transform: translateX(4px); }

    .sf-item { display: flex; gap: .85rem; margin-bottom: 1.1rem; }
    .sf-item:last-child { margin-bottom: 0; }
    .sf-icon { width: 34px; height: 34px; border-radius: 50%; background: rgba(201,168,76,.18); color: var(--gold-dark); display: grid; place-items: center; font-size: .85rem; flex-shrink: 0; }
    .sf-item strong { display: block; color: #2b2417; font-weight: 600; font-size: .92rem; margin-bottom: .1rem; }
    .sf-item span, .sf-item a { line-height: 1.65; }
    .sf-item a:hover { color: var(--gold-dark); }

    /* Bottom bar */
    .sf-bottom { border-top: 1px solid rgba(201,168,76,.4); padding: 1.25rem 0; font-size: .82rem; color: #6f6650; }
    .sf-bottom-inner { display: flex; justify-content: center; text-align: center; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .sf-legal { display: flex; justify-content: center; flex-wrap: wrap; gap: .35rem 1.6rem; padding-bottom: .9rem; margin-bottom: .9rem; border-bottom: 1px solid rgba(201,168,76,.28); }
    .sf-legal a { color: #4a412c; font-weight: 500; transition: color .2s; }
    .sf-legal a:hover { color: var(--gold-dark); text-decoration: underline; }
    .sf-bottom a { color: var(--gold-dark); }
    .sf-bottom a:hover { text-decoration: underline; }

    @media (max-width: 767px) {
        .sf-main { padding-top: 2.5rem; padding-bottom: 1.5rem; }
        .sf-bottom { padding-bottom: 4.5rem; } /* keep the floating WhatsApp button off the text */
    }
</style>

<footer class="sf" aria-label="Site footer">
    {{-- Main columns --}}
    <div class="container sf-main">
        <div class="row g-4 g-lg-5">
            <div class="{{ $brandCol }} col-md-6">
                <a href="{{ route('home') }}" class="sf-brand">
                    <span class="sf-brand-mark">
                        @if($siteLogo)
                            <img src="{{ $logoUrl }}" alt="JOSHA INFINITY INC SERVICE" loading="lazy">
                        @else
                            <i class="fas fa-infinity"></i>
                        @endif
                    </span>
                    <span>
                        <span class="sf-brand-name d-block">JOSHA INFINITY<br>INC SERVICE&reg;</span>
                        <span class="sf-brand-sub d-block">Gold &amp; Silver Solutions</span>
                    </span>
                </a>
                <p class="sf-about">Daily gold and silver rates, published each morning and evening, with clear pricing by purity and weight.</p>

                @if($socialLinks->isNotEmpty())
                <div class="sf-social">
                    @foreach($socialLinks as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener" aria-label="{{ $link->platform }}" title="{{ $link->platform }}">
                            <i class="{{ $link->icon ?: 'fas fa-link' }}"></i>
                        </a>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="{{ $linksCol }} col-md-6 col-6">
                <h4 class="sf-title">Quick Links</h4>
                <ul class="sf-links">
                    @foreach($quickLinks as [$label, $url])
                        <li><a href="{{ $url }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            @if($hasOffices)
            <div class="col-lg-3 col-md-6 col-12">
                <h4 class="sf-title">Our Offices</h4>
                @foreach($offices as $office)
                    <div class="sf-item">
                        <span class="sf-icon"><i class="fas fa-location-dot"></i></span>
                        <div>
                            @if(filled($office->title))<strong>{{ $office->title }}</strong>@endif
                            @if(filled($office->address))<span>{!! nl2br(e($office->address)) !!}</span>@endif
                            @if(filled($office->phone) || filled($office->mobile))
                                <div><a href="tel:{{ preg_replace('/[^\d+]/', '', $office->phone ?: $office->mobile) }}">{{ $office->phone ?: $office->mobile }}</a></div>
                            @endif
                            @if(filled($office->email))
                                <div><a href="mailto:{{ $office->email }}" style="word-break: break-all;">{{ $office->email }}</a></div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            @if($hasContact)
            <div class="col-lg-3 col-md-6">
                <h4 class="sf-title">Get in Touch</h4>
                @if($phone !== '')
                <div class="sf-item">
                    <span class="sf-icon"><i class="fas fa-phone"></i></span>
                    <div><strong>Call us</strong><a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a></div>
                </div>
                @endif
                @if($email !== '')
                <div class="sf-item">
                    <span class="sf-icon"><i class="fas fa-envelope"></i></span>
                    <div><strong>Email</strong><a href="mailto:{{ $email }}" style="word-break: break-all;">{{ $email }}</a></div>
                </div>
                @endif
                @if($whatsapp !== '')
                <div class="sf-item">
                    <span class="sf-icon"><i class="fab fa-whatsapp"></i></span>
                    <div><strong>WhatsApp</strong><a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener">Chat with us</a></div>
                </div>
                @endif
                @if($hours !== '')
                <div class="sf-item">
                    <span class="sf-icon"><i class="fas fa-clock"></i></span>
                    <div><strong>Working hours</strong><span>{!! nl2br(e($hours)) !!}</span></div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="sf-bottom">
        @if($footerPages->isNotEmpty())
        <nav class="container sf-legal" aria-label="Legal and information pages">
            @foreach($footerPages as $footerPage)
                <a href="{{ route('pages.show', ['page' => $footerPage->slug]) }}">{{ $footerPage->title }}</a>
            @endforeach
        </nav>
        @endif
        <div class="container sf-bottom-inner">
            <div>&copy; {{ date('Y') }} JOSHA INFINITY INC SERVICE&reg;. All Rights Reserved.</div>
        </div>
    </div>
</footer>
