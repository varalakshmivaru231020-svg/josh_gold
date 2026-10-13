{{-- Scripts for live-rates._chart (amCharts 3 stock, same as the live rates page) --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/amcharts3@3.21.15/amcharts/amcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/amcharts3@3.21.15/amcharts/serial.js"></script>
<script src="https://cdn.jsdelivr.net/npm/amstock3@3.21.15/amcharts/amstock.js"></script>
<script src="{{ asset('js/rate-chart.js') }}?v={{ filemtime(public_path('js/rate-chart.js')) }}"></script>
@endpush
