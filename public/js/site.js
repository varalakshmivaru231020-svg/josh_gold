/*
 * Light motion for the public pages, no libraries:
 *   - header: .is-scrolled once the page moves (solid background on the home page)
 *   - [data-reveal]: fades and lifts sections in as they scroll into view
 *   - [data-parallax="0.06"]: gentle hero parallax
 *   - .jb-faq details: smooth open and close
 * Everything is skipped for visitors who ask for reduced motion; content is never hidden without JS
 * (the reveal styles only apply under html.jb-js, which jb-head sets).
 */
(function () {
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Header state
    var header = document.querySelector('[data-header]');
    if (header) {
        var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 24); };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // Scroll reveal
    var reveals = document.querySelectorAll('[data-reveal]');
    if (reveals.length) {
        if (reduce || !('IntersectionObserver' in window)) {
            reveals.forEach(function (el) { el.classList.add('is-visible'); });
        } else {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                });
            }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
            reveals.forEach(function (el) { io.observe(el); });
        }
    }

    // Hero parallax (desktop, pointer devices only)
    var layers = document.querySelectorAll('[data-parallax]');
    if (layers.length && !reduce && window.matchMedia('(min-width: 1000px) and (hover: hover)').matches) {
        var ticking = false;
        var update = function () {
            var y = window.scrollY;
            if (y < window.innerHeight * 1.2) {
                layers.forEach(function (el) {
                    el.style.transform = 'translate3d(0,' + (y * parseFloat(el.getAttribute('data-parallax'))).toFixed(1) + 'px,0)';
                });
            }
            ticking = false;
        };
        window.addEventListener('scroll', function () {
            if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
        }, { passive: true });
        update();
    }

    // FAQ: animate the answer open and closed (the <details> element still works without JS)
    if (!reduce && Element.prototype.animate) {
        document.querySelectorAll('.jb-faq details').forEach(function (details) {
            var summary = details.querySelector('summary');
            var body = summary && summary.nextElementSibling;
            if (!body) return;
            var running = null;
            summary.addEventListener('click', function (e) {
                e.preventDefault();
                if (running) running.cancel();
                var opening = !details.open;
                if (opening) details.open = true;
                var full = body.scrollHeight;
                body.style.overflow = 'hidden';
                running = body.animate(
                    opening ? [{ height: '0px', opacity: 0 }, { height: full + 'px', opacity: 1 }]
                            : [{ height: full + 'px', opacity: 1 }, { height: '0px', opacity: 0 }],
                    { duration: 320, easing: 'cubic-bezier(.22,.61,.36,1)' }
                );
                running.onfinish = function () {
                    if (!opening) details.open = false;
                    body.style.overflow = '';
                    running = null;
                };
            });
        });
    }
})();
