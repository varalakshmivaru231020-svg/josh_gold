{{--
    Cookie banner and cookie settings dialog, included once from layouts/partials/jb-footer.
    Behaviour: public/js/consent.js (window.JoshaConsent). Styles: public/css/consent.css.

    Two categories: Essential (always on: the session cookie, XSRF-TOKEN, josha_consent) and
    Embedded content (Google Maps on Contact, TradingView charts on Charts), off by default.
    The site uses no analytics or advertising cookies. Any element with data-consent-open
    opens the dialog; embeds use <div class="jb-embed" data-consent-embed data-embed-src="…">.
--}}
@php $jcPolicy = url('/cookie-policy'); @endphp

<section class="jbc-banner" id="jbcBanner" aria-labelledby="jbcBannerTitle" hidden>
    <div class="jbc-banner__inner">
        <div class="jbc-banner__text">
            <h2 class="jbc-banner__title" id="jbcBannerTitle">Cookies on this site</h2>
            <p>We only use the cookies the site needs to work. We don't use analytics or advertising cookies.
                Maps and price charts come from Google and TradingView, which may set their own cookies, so we load them only if you allow it.
                <a href="{{ $jcPolicy }}">Cookie policy</a></p>
        </div>
        <div class="jbc-banner__actions">
            <button type="button" class="jb-btn jbc-choice" data-consent-accept>Accept all</button>
            <button type="button" class="jb-btn jbc-choice" data-consent-reject>Reject non-essential</button>
            <button type="button" class="jb-btn jbc-choice" data-consent-open>Customize</button>
        </div>
    </div>
</section>

<div class="jbc-dialog" id="jbcDialog" hidden>
    <div class="jbc-dialog__backdrop" data-consent-close></div>
    <div class="jbc-dialog__panel" role="dialog" aria-modal="true" aria-labelledby="jbcDialogTitle" aria-describedby="jbcDialogIntro" tabindex="-1">
        <div class="jbc-dialog__head">
            <h2 class="jbc-dialog__title" id="jbcDialogTitle">Cookie settings</h2>
            <button type="button" class="jbc-dialog__close" data-consent-close aria-label="Close cookie settings">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
        <p class="jbc-dialog__intro" id="jbcDialogIntro">Choose what this site may load. We don't use analytics or advertising cookies.
            You can change this at any time with “Cookie settings” at the bottom of every page.</p>

        <div class="jbc-cat">
            <div class="jbc-cat__head">
                <label class="jbc-cat__name" for="jbcEssential">Essential</label>
                <span class="jbc-cat__state">Always on</span>
                <input class="jbc-switch" type="checkbox" role="switch" id="jbcEssential" checked disabled aria-describedby="jbcEssentialDesc">
            </div>
            <p class="jbc-cat__desc" id="jbcEssentialDesc">Needed for the site to work and stay secure: the session cookie,
                XSRF-TOKEN (protects forms) and josha_consent (remembers this choice for 12 months). They can't be switched off.</p>
        </div>

        <div class="jbc-cat">
            <div class="jbc-cat__head">
                <label class="jbc-cat__name" for="jbcEmbedded">Embedded content</label>
                <span class="jbc-cat__state" data-consent-state aria-hidden="true">Off</span>
                <input class="jbc-switch" type="checkbox" role="switch" id="jbcEmbedded" aria-describedby="jbcEmbeddedDesc">
            </div>
            <p class="jbc-cat__desc" id="jbcEmbeddedDesc">Google Maps on the Contact page and TradingView price charts on the Charts page.
                These come from other companies and may set their own cookies. Until you turn this on, you'll see a button to load each one yourself.</p>
        </div>

        <p class="jbc-dialog__more"><a href="{{ $jcPolicy }}">Read the cookie policy</a></p>

        <div class="jbc-dialog__actions">
            <button type="button" class="jb-btn jbc-choice" data-consent-save>Save choices</button>
            <button type="button" class="jb-btn jbc-choice" data-consent-accept>Accept all</button>
            <button type="button" class="jb-btn jbc-choice" data-consent-reject>Reject non-essential</button>
        </div>
    </div>
</div>
