/*
 * Live spot prices (ticker, home page): polls /live-rates/spot every 15 s while the tab is visible and
 * updates every [data-spot="key"] element. A value that moved flashes green (up) or red (down) and its
 * [data-spot-dir] arrow keeps the direction until the next move.
 */
(function () {
    var nodes = document.querySelectorAll('[data-spot]');
    if (!nodes.length) return;

    var url = document.documentElement.getAttribute('data-spot-url') || '/live-rates/spot';
    var last = {};
    var inr = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 });
    var formats = {
        usd2: function (v) { return '$' + v.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); },
        usd3: function (v) { return '$' + v.toLocaleString('en-US', { minimumFractionDigits: 3, maximumFractionDigits: 3 }); },
        fx:   function (v) { return '₹' + v.toFixed(2); },
        inr:  function (v) { return '₹' + inr.format(v); }
    };

    nodes.forEach(function (n) {
        var v = parseFloat(n.getAttribute('data-value'));
        if (!isNaN(v)) last[n.getAttribute('data-spot')] = v;
    });

    function apply(data) {
        Object.keys(data).forEach(function (key) {
            var v = parseFloat(data[key]);
            if (isNaN(v)) return;
            var prev = last[key];
            var dir = prev === undefined || v === prev ? null : (v > prev ? 'up' : 'down');
            last[key] = v;

            document.querySelectorAll('[data-spot="' + key + '"]').forEach(function (n) {
                var fmt = formats[n.getAttribute('data-format')] || formats.inr;
                n.textContent = fmt(v);
                if (!dir) return;
                var item = n.closest('[data-spot-item]') || n;
                item.classList.remove('is-flash-up', 'is-flash-down');
                void item.offsetWidth; // restart the animation
                item.classList.add('is-flash-' + dir);
                var arrow = item.querySelector('[data-spot-dir]');
                if (arrow) {
                    arrow.textContent = dir === 'up' ? '▲' : '▼';
                    arrow.className = 'jb-spot-dir jb-spot-dir--' + dir;
                }
            });
        });
        document.querySelectorAll('[data-spot-time]').forEach(function (t) {
            if (data.updated_at) {
                var d = new Date(data.updated_at);
                t.textContent = d.toLocaleTimeString('en-IN', { hour: 'numeric', minute: '2-digit', second: '2-digit' });
            }
        });
    }

    function poll() {
        if (document.hidden) return;
        fetch(url, { headers: { Accept: 'application/json' } })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (d) { if (d) apply(d); })
            .catch(function () {});
    }

    poll();
    setInterval(poll, 15000);
    document.addEventListener('visibilitychange', function () { if (!document.hidden) poll(); });
})();
