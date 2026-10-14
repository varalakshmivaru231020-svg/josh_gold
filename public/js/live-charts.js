/*
 * Our own live candle charts (/charts and /charts/{slug}), drawn with Apache ECharts (public/js/vendor, self-hosted).
 * Each [data-lc-chart] element loads candles from /live-rates/candles (built from the spot prices the server saves
 * every minute) and refreshes every 15 s. Green candle = price rose in that period, red = fell.
 * Markup: <div data-lc-chart="xauusd" data-dp="2" data-tf="5"> inside a .lc-chart card that may hold
 * [data-tf-btn="1|5|15|60"] buttons, a [data-lc-ohlc] line, a [data-lc-empty] message and a [data-lc-sample] tag
 * (shown while flagged sample history is in view; see AppSupportSpotPrices::addSample).
 */
(function () {
    if (!window.echarts) return;
    var nodes = document.querySelectorAll('[data-lc-chart]');
    if (!nodes.length) return;

    var url = document.documentElement.getAttribute('data-candles-url') || '/live-rates/candles';
    var UP = '#22c55e', DOWN = '#ef4444', GRID = '#1f1c17', MUTED = '#8d877b';
    var small = window.matchMedia('(max-width: 760px)');

    function pad(n) { return (n < 10 ? '0' : '') + n; }
    function timeLabel(ms, tf) {
        var d = new Date(ms);
        var hm = pad(d.getHours()) + ':' + pad(d.getMinutes());
        if (tf >= 60) return d.getDate() + ' ' + d.toLocaleString('en-IN', { month: 'short' }) + (d.getHours() ? ' ' + hm : '');
        return hm;
    }

    nodes.forEach(function (el) {
        var key = el.getAttribute('data-lc-chart');
        var dp = parseInt(el.getAttribute('data-dp') || '2', 10);
        var tf = parseInt(el.getAttribute('data-tf') || '5', 10);
        var card = el.closest('.lc-chart') || el.parentNode;
        var ohlc = card.querySelector('[data-lc-ohlc]');
        var empty = card.querySelector('[data-lc-empty]');
        var sampleTag = card.querySelector('[data-lc-sample]');
        var fmt = function (v) { return Number(v).toLocaleString('en-US', { minimumFractionDigits: dp, maximumFractionDigits: dp }); };
        var chart = echarts.init(el, null, { renderer: 'canvas' });
        var shown = 0;

        function option(candles) {
            var times = candles.map(function (c) { return timeLabel(c[0], tf); });
            var data = candles.map(function (c) { return [c[1], c[2], c[3], c[4]]; });
            var last = candles[candles.length - 1];
            var lastUp = last && last[2] >= last[1];
            var zoomStart = candles.length > (small.matches ? 60 : 120) ? 100 - Math.round((small.matches ? 60 : 120) / candles.length * 100) : 0;
            return {
                animation: false,
                backgroundColor: 'transparent',
                grid: { left: 6, right: small.matches ? 70 : 74, top: 12, bottom: 26 },
                tooltip: {
                    trigger: 'axis', axisPointer: { type: 'cross', lineStyle: { color: '#5a5245' }, crossStyle: { color: '#5a5245' }, label: { backgroundColor: '#2c2820' } },
                    backgroundColor: '#14120e', borderColor: '#3a352c', textStyle: { color: '#ece6da', fontSize: 12 },
                    formatter: function (p) {
                        var c = p[0] && p[0].data; if (!c) return '';
                        return p[0].axisValue + '<br>O ' + fmt(c[1]) + '&nbsp; H ' + fmt(c[4]) + '<br>L ' + fmt(c[3]) + '&nbsp; C ' + fmt(c[2]);
                    }
                },
                xAxis: {
                    type: 'category', data: times, boundaryGap: true,
                    axisLine: { lineStyle: { color: '#3a352c' } }, axisTick: { show: false },
                    axisLabel: { color: MUTED, fontSize: 11, hideOverlap: true },
                    splitLine: { show: true, lineStyle: { color: GRID } }
                },
                yAxis: {
                    type: 'value', scale: true, position: 'right',
                    axisLine: { show: false }, axisTick: { show: false },
                    axisLabel: { color: MUTED, fontSize: 11, formatter: function (v) { return fmt(v); } },
                    splitLine: { lineStyle: { color: GRID } }
                },
                dataZoom: [{ type: 'inside', start: zoomStart, end: 100, minValueSpan: 10 }],
                series: [{
                    type: 'candlestick', data: data, barMaxWidth: 12,
                    itemStyle: { color: UP, color0: DOWN, borderColor: UP, borderColor0: DOWN },
                    markLine: last ? {
                        symbol: 'none', silent: true, animation: false,
                        lineStyle: { color: lastUp ? UP : DOWN, type: 'dashed', width: 1 },
                        label: { position: 'end', formatter: function () { return fmt(last[2]); }, color: '#fff', backgroundColor: lastUp ? UP : DOWN, padding: [3, 5], borderRadius: 3, fontSize: 11, fontWeight: 700 },
                        data: [{ yAxis: last[2] }]
                    } : undefined
                }]
            };
        }

        function render(candles, sample) {
            if (sampleTag) sampleTag.hidden = !sample;
            if (empty) empty.hidden = candles.length >= 3;
            if (!candles.length) { chart.clear(); return; }
            var opt = option(candles);
            if (shown) delete opt.dataZoom; // keep the visitor's zoom on refresh
            chart.setOption(opt, { lazyUpdate: true });
            shown = candles.length;
            var c = candles[candles.length - 1];
            if (ohlc) {
                var up = c[2] >= c[1];
                ohlc.innerHTML = 'O <b>' + fmt(c[1]) + '</b> H <b>' + fmt(c[4]) + '</b> L <b>' + fmt(c[3]) + '</b> C <b class="' + (up ? 'is-up' : 'is-down') + '">' + fmt(c[2]) + '</b>';
            }
        }

        function load() {
            if (document.hidden) return;
            fetch(url + '?chart=' + encodeURIComponent(key) + '&tf=' + tf, { headers: { Accept: 'application/json' } })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (d) { if (d && d.candles) render(d.candles, !!d.sample); })
                .catch(function () {});
        }

        card.querySelectorAll('[data-tf-btn]').forEach(function (btn) {
            btn.setAttribute('aria-pressed', String(parseInt(btn.getAttribute('data-tf-btn'), 10) === tf));
            btn.addEventListener('click', function () {
                tf = parseInt(btn.getAttribute('data-tf-btn'), 10);
                card.querySelectorAll('[data-tf-btn]').forEach(function (b) { b.setAttribute('aria-pressed', String(b === btn)); });
                shown = 0;
                chart.clear();
                load();
            });
        });

        if (window.ResizeObserver) new ResizeObserver(function () { chart.resize(); }).observe(el);
        else window.addEventListener('resize', function () { chart.resize(); });

        load();
        setInterval(load, 15000);
        document.addEventListener('visibilitychange', function () { if (!document.hidden) load(); });
    });
})();
