/*
 * Cookie consent for the public site (markup: resources/views/layouts/partials/consent.blade.php).
 *
 * Categories: Essential (always on) and Embedded content (third-party iframes such as Google Maps
 * and TradingView, off until the visitor allows them). The choice is kept in a first-party cookie:
 *
 *   josha_consent = {"v":1,"embedded":true|false,"ts":<unix seconds>}   SameSite=Lax, Secure on https, 12 months
 *
 * API
 *   JoshaConsent.get()      → { v, embedded, ts, decided }  (decided is false until a choice is saved)
 *   JoshaConsent.set(prefs) → saves { embedded }, applies it and fires a "josha:consent" event on document
 *   JoshaConsent.open()     → opens the cookie settings dialog
 *   Any element with [data-consent-open] opens the dialog as well.
 *
 * Embeds
 *   <div class="jb-embed" data-consent-embed data-embed-src="https://…" data-embed-title="…" data-embed-height="360">
 *       …placeholder… <button type="button" data-embed-load>Load …</button>
 *   </div>
 *   With embedded consent the placeholder is swapped for an iframe; [data-embed-load] loads that one
 *   embed once without saving a preference; [data-embed-always] (added automatically if missing)
 *   saves consent. Turning embedded content off puts the placeholders back.
 */
(function () {
    'use strict';

    var COOKIE = 'josha_consent';
    var VERSION = 1;
    var MAX_AGE = 60 * 60 * 24 * 365; // 12 months
    var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])';

    var banner = document.getElementById('jbcBanner');
    var dialog = document.getElementById('jbcDialog');
    var panel = dialog && dialog.querySelector('[role="dialog"]');
    var embeddedSwitch = document.getElementById('jbcEmbedded');
    var stateLabel = dialog && dialog.querySelector('[data-consent-state]');
    var root = document.documentElement;

    var placeholders = typeof WeakMap === 'function' ? new WeakMap() : null;
    var returnFocus = null;
    var madeInert = [];

    /* ── Cookie ─────────────────────────────────────── */
    function read() {
        var match = document.cookie.match(/(?:^|;\s*)josha_consent=([^;]*)/);
        if (!match) return null;
        try {
            var value = JSON.parse(decodeURIComponent(match[1]));
            if (!value || value.v !== VERSION) return null;
            return { v: VERSION, embedded: value.embedded === true, ts: Number(value.ts) || 0 };
        } catch (e) {
            return null;
        }
    }

    function write(embedded) {
        var value = { v: VERSION, embedded: !!embedded, ts: Math.floor(Date.now() / 1000) };
        document.cookie = COOKIE + '=' + encodeURIComponent(JSON.stringify(value)) +
            '; Max-Age=' + MAX_AGE + '; Path=/; SameSite=Lax' +
            (location.protocol === 'https:' ? '; Secure' : '');
        return value;
    }

    var current = read();

    function get() {
        return current
            ? { v: current.v, embedded: current.embedded, ts: current.ts, decided: true }
            : { v: VERSION, embedded: false, ts: null, decided: false };
    }

    /* ── Embeds ─────────────────────────────────────── */
    function embeds() {
        return Array.prototype.slice.call(document.querySelectorAll('[data-consent-embed]'));
    }

    function embedHeight(el) {
        var h = parseInt(el.getAttribute('data-embed-height'), 10);
        return h > 0 ? h : 360;
    }

    function prepare(el) {
        if (el.hasAttribute('data-embed-ready')) return;
        el.setAttribute('data-embed-ready', '');
        el.style.minHeight = embedHeight(el) + 'px';
        if (!el.querySelector('[data-embed-always]')) {
            var row = document.createElement('p');
            row.className = 'jb-embed__always';
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('data-embed-always', '');
            btn.textContent = 'Always allow embedded content';
            row.appendChild(btn);
            var host = el.querySelector('.jb-embed__placeholder') || el;
            host.appendChild(row);
        }
    }

    function load(el, moveFocus) {
        if (el.classList.contains('is-loaded')) return;
        var src = el.getAttribute('data-embed-src') || '';
        if (!/^https:\/\//i.test(src)) return;

        var hadFocus = el.contains(document.activeElement);
        var saved = document.createDocumentFragment();
        while (el.firstChild) saved.appendChild(el.firstChild);
        if (placeholders) placeholders.set(el, saved); else el._jbcPlaceholder = saved;

        var frame = document.createElement('iframe');
        frame.src = src;
        frame.title = el.getAttribute('data-embed-title') || 'Embedded content';
        frame.setAttribute('loading', 'lazy');
        frame.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
        frame.style.cssText = 'width:100%;height:' + embedHeight(el) + 'px;border:0';
        el.appendChild(frame);
        el.classList.add('is-loaded');

        // Keep keyboard users where they were instead of dropping focus to the page start
        if (moveFocus || hadFocus) {
            if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex', '-1');
            el.focus({ preventScroll: true });
        }
    }

    function unload(el) {
        if (!el.classList.contains('is-loaded')) return;
        var hadFocus = el.contains(document.activeElement) || document.activeElement === el;
        var saved = placeholders ? placeholders.get(el) : el._jbcPlaceholder;
        el.textContent = '';
        if (saved) el.appendChild(saved);
        el.classList.remove('is-loaded');
        if (hadFocus) {
            var btn = el.querySelector('[data-embed-load]');
            if (btn) btn.focus({ preventScroll: true });
        }
    }

    function applyEmbeds() {
        var allowed = !!(current && current.embedded);
        embeds().forEach(function (el) {
            prepare(el);
            if (allowed) load(el, false); else unload(el);
        });
    }

    /* ── Banner ─────────────────────────────────────── */
    function syncBannerSize() {
        if (!banner || banner.hidden) return;
        root.style.setProperty('--jbc-banner-h', banner.offsetHeight + 'px');
    }

    function showBanner(show) {
        if (!banner) return;
        if (show && banner.parentNode !== document.body.firstElementChild) {
            // First in the page so keyboard and screen-reader users meet it before anything else
            document.body.insertBefore(banner, document.body.firstChild);
        }
        banner.hidden = !show;
        root.classList.toggle('jbc-has-banner', show);
        if (show) syncBannerSize(); else root.style.removeProperty('--jbc-banner-h');
    }

    /* ── Saving ─────────────────────────────────────── */
    function set(prefs) {
        var embedded = !!(prefs && prefs.embedded);
        current = write(embedded);
        showBanner(false);
        applyEmbeds();
        var detail = get();
        var event;
        try {
            event = new CustomEvent('josha:consent', { bubbles: true, detail: detail });
        } catch (e) {
            event = document.createEvent('CustomEvent');
            event.initCustomEvent('josha:consent', true, false, detail);
        }
        document.dispatchEvent(event);
        return detail;
    }

    /* ── Dialog ─────────────────────────────────────── */
    function syncSwitchLabel() {
        if (stateLabel && embeddedSwitch) stateLabel.textContent = embeddedSwitch.checked ? 'On' : 'Off';
    }

    function setBackgroundInert(on) {
        if (on) {
            Array.prototype.forEach.call(document.body.children, function (el) {
                if (el === dialog || el.tagName === 'SCRIPT' || el.hasAttribute('inert')) return;
                el.setAttribute('inert', '');
                madeInert.push(el);
            });
        } else {
            madeInert.forEach(function (el) { el.removeAttribute('inert'); });
            madeInert = [];
        }
    }

    function isOpen() {
        return !!dialog && !dialog.hidden;
    }

    function open() {
        if (!dialog || isOpen()) return;
        returnFocus = document.activeElement && document.activeElement !== document.body ? document.activeElement : null;
        if (embeddedSwitch) embeddedSwitch.checked = !!(current && current.embedded);
        syncSwitchLabel();
        dialog.hidden = false;
        root.classList.add('jbc-locked');
        setBackgroundInert(true);
        panel.focus();
    }

    function close() {
        if (!isOpen()) return;
        dialog.hidden = true;
        root.classList.remove('jbc-locked');
        setBackgroundInert(false);
        var target = returnFocus;
        returnFocus = null;
        if (target && document.contains(target) && target.offsetParent !== null) {
            target.focus();
        } else {
            focusPageStart(); // the opener has gone, e.g. "Customize" in the banner that just closed
        }
    }

    // Put keyboard focus back at the top of the page without scrolling, so the next Tab
    // lands on the skip link (used when the focused banner or dialog button disappears).
    function focusPageStart() {
        var body = document.body;
        body.setAttribute('tabindex', '-1');
        body.focus({ preventScroll: true });
        body.addEventListener('blur', function drop() {
            body.removeAttribute('tabindex');
            body.removeEventListener('blur', drop);
        });
    }

    function trapTab(e) {
        var items = Array.prototype.filter.call(panel.querySelectorAll(FOCUSABLE), function (el) {
            return el.offsetParent !== null;
        });
        if (!items.length) { e.preventDefault(); panel.focus(); return; }
        var first = items[0];
        var last = items[items.length - 1];
        var active = document.activeElement;
        if (e.shiftKey && (active === first || active === panel || !panel.contains(active))) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && (active === last || !panel.contains(active))) {
            e.preventDefault();
            first.focus();
        }
    }

    /* ── Wiring ─────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        var t = e.target && e.target.closest ? e.target : null;
        if (!t) return;
        var hit;

        if ((hit = t.closest('[data-consent-open]'))) {
            e.preventDefault();
            open();
        } else if ((hit = t.closest('[data-consent-accept], [data-consent-reject], [data-consent-save]'))) {
            var embedded = hit.hasAttribute('data-consent-accept') ||
                (hit.hasAttribute('data-consent-save') && !!(embeddedSwitch && embeddedSwitch.checked));
            var fromBanner = !!banner && banner.contains(hit);
            set({ embedded: embedded });
            if (isOpen()) close(); else if (fromBanner) focusPageStart();
        } else if ((hit = t.closest('[data-consent-close]'))) {
            close();
        } else if ((hit = t.closest('[data-embed-always]'))) {
            e.preventDefault();
            var box = hit.closest('[data-consent-embed]');
            set({ embedded: true });
            if (box) { box.setAttribute('tabindex', '-1'); box.focus({ preventScroll: true }); }
        } else if ((hit = t.closest('[data-embed-load]'))) {
            e.preventDefault();
            var embed = hit.closest('[data-consent-embed]');
            if (embed) { prepare(embed); load(embed, true); }
        }
    });

    document.addEventListener('keydown', function (e) {
        if (!isOpen()) return;
        if (e.key === 'Escape' || e.key === 'Esc') {
            e.preventDefault();
            e.stopPropagation();
            close();
        } else if (e.key === 'Tab') {
            trapTab(e);
        }
    }, true);

    if (embeddedSwitch) embeddedSwitch.addEventListener('change', syncSwitchLabel);
    window.addEventListener('resize', syncBannerSize);

    // Footer "Cookie settings" is hidden until this script runs, since it needs JavaScript
    Array.prototype.forEach.call(document.querySelectorAll('[data-consent-open][hidden]'), function (el) {
        el.hidden = false;
    });

    window.JoshaConsent = { get: get, set: set, open: open };

    showBanner(!current);
    applyEmbeds();
})();
