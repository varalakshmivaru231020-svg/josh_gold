/**
 * Gold / Silver rate chart (amCharts 3 "stock" chart).
 *
 * Draws the price history published in admin. Each chart loads its daily closing
 * rates once from GET /live-rates/history (LiveRatesController@history, range=MAX)
 * and the Zoom buttons, From/To boxes and the mini graph then work on that data
 * in the browser. Nothing here generates or fills in prices.
 *
 * Markup: resources/views/live-rates/_chart.blade.php
 * Needs amcharts.js, serial.js, themes/light.js and amstock.js to be loaded first.
 */
(function () {
    'use strict';

    // Same zoom buttons as bullions.co.in. Returns a NEW array each time: amCharts stores
    // each chart's button elements on these objects, so charts must never share them.
    function periods() {
        return [
            { period: 'DD', count: 7, label: '1W' },
            { period: 'MM', count: 1, label: '1M' },
            { period: 'MM', count: 3, label: '3M' },
            { period: 'MM', count: 6, label: '6M' },
            { period: 'YYYY', count: 1, label: '1Y' },
            { period: 'YYYY', count: 2, label: '2Y' },
            { period: 'YYYY', count: 3, label: '3Y' },
            { period: 'YYYY', count: 5, label: '5Y' },
            { period: 'YTD', label: 'YTD' },
            { period: 'MAX', label: 'MAX', selected: true }
        ];
    }

    // API rows [{timestamp: "2026-09-26T17:00:00+05:30", price}] -> chart rows.
    // The date part of the timestamp is already the Indian (IST) calendar date.
    function toRows(data) {
        var rows = [];
        (Array.isArray(data) ? data : []).forEach(function (point) {
            var match = point && /^(\d{4})-(\d{2})-(\d{2})/.exec(point.timestamp);
            var price = typeof point.price === 'string' ? parseFloat(point.price) : point.price;
            if (match && typeof price === 'number' && isFinite(price)) {
                rows.push({ date: new Date(+match[1], +match[2] - 1, +match[3]), value: price });
            }
        });
        rows.sort(function (a, b) { return a.date - b.date; });
        return rows;
    }

    // Colours for the dark panel the chart sits on (resources/views/live-rates/_chart.blade.php).
    // Line colours still come from the markup (data-line / data-strip).
    var DARK = {
        text: '#c9c9c9',      // axis labels, "JS chart by amCharts"
        title: '#e6e6e6',     // title inside the graph
        grid: '#ffffff',      // drawn at a low alpha
        gridAlpha: 0.07,
        strip: '#141417',     // mini graph background
        balloon: '#0d0d10'    // rate box fill (its border takes the line colour)
    };

    function init(root) {
        var chartEl = root.querySelector('.rc-chart');
        var retry = root.querySelector('.rc-retry');
        var chart = null;

        function setState(state) {
            root.dataset.state = state;
        }

        function draw(rows) {
            if (chart) { chart.clear(); }

            chart = AmCharts.makeChart(chartEl.id, {
                type: 'stock',
                pathToImages: root.dataset.images,
                numberFormatter: { precision: 0, decimalSeparator: '.', thousandsSeparator: ',' },

                // Rate box: dark fill, white text, border in the line colour
                balloon: { fillColor: DARK.balloon, fillAlpha: 0.95, color: '#ffffff' },

                categoryAxesSettings: {
                    minPeriod: 'DD',
                    parseDates: true,
                    groupToPeriods: ['DD'], // exact daily points, no averaging
                    dashLength: 1,
                    minorGridEnabled: true,
                    color: DARK.text,
                    gridColor: DARK.grid,
                    gridAlpha: DARK.gridAlpha,
                    minorGridAlpha: 0.035
                },

                dataSets: [{
                    color: root.dataset.line,
                    fieldMappings: [{ fromField: 'value', toField: 'value' }],
                    dataProvider: rows,
                    categoryField: 'date'
                }],

                panels: [{
                    showCategoryAxis: true,
                    title: '',
                    eraseAll: false,
                    allLabels: [{ x: 60, y: 20, text: root.dataset.title, size: 12, bold: true, color: DARK.title }],
                    stockGraphs: [{ id: 'g1', valueField: 'value', lineThickness: 2, useDataSetColors: true, precision: 0 }],
                    stockLegend: { valueTextRegular: '', markerType: 'none' }
                }],

                // The mini graph under the chart, with drag handles
                chartScrollbarSettings: {
                    graph: 'g1',
                    enabled: true,
                    backgroundColor: DARK.strip,
                    selectedBackgroundColor: root.dataset.strip,
                    graphFillColor: root.dataset.line,
                    selectedGraphFillColor: root.dataset.line,
                    selectedBackgroundAlpha: 0.16,
                    backgroundAlpha: 1,
                    graphFillAlpha: 0.45,
                    selectedGraphFillAlpha: 0.9,
                    color: DARK.text,
                    gridColor: DARK.grid,
                    gridAlpha: 0.06,
                    hideResizeGrips: false
                },

                // 0, 50,000, 100,000 ... labels inside the chart, like the reference
                valueAxesSettings: { inside: true, autoGridCount: false, gridCount: 4, color: DARK.text, gridColor: DARK.grid, gridAlpha: DARK.gridAlpha },

                // Red cursor line + red date label + rate box
                chartCursorSettings: {
                    valueBalloonsEnabled: true,
                    cursorPosition: 'mouse',
                    pan: true,
                    cursorColor: '#CC0000',
                    categoryBalloonColor: '#CC0000'
                },

                // "Zoom:" buttons and the From / To date boxes
                periodSelector: { position: 'bottom', periods: periods() },

                panelsSettings: { usePrefixes: false, color: DARK.text },
                export: { enabled: false }
            });
        }

        function load() {
            setState('loading');

            fetch(root.dataset.endpoint, { headers: { Accept: 'application/json' }, credentials: 'same-origin' })
                .then(function (response) {
                    if (!response.ok) { throw new Error('HTTP ' + response.status); }
                    return response.json();
                })
                .then(function (json) {
                    var rows = toRows(json && json.data);
                    if (!rows.length) {
                        setState('empty');
                        return;
                    }
                    setState('ready'); // visible before amCharts measures the container
                    draw(rows);
                })
                .catch(function (error) {
                    if (window.console) { console.error('Rate chart failed:', error); }
                    setState('error');
                });
        }

        retry.addEventListener('click', load);

        if (typeof AmCharts === 'undefined' || !AmCharts.makeChart) {
            setState('error');
            return;
        }
        load();
    }

    document.querySelectorAll('[data-rate-chart]').forEach(init);
})();
