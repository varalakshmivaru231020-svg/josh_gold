{{-- Admin sidebar: only the modules the public website uses. Links whose route does not exist are skipped. --}}
@php
    $unreadCount = \App\Models\ContactMessage::unread()->count();

    // Offices is a tab of Site Settings, so it wins over Site Settings when that tab is open
    $officesActive  = Route::is('admin.site-content.offices*')
        || (Route::is('admin.site-content.settings') && request('tab') === 'offices');
    $settingsActive = ! $officesActive
        && Route::is('admin.site-content.settings*', 'admin.site-content.contact-settings*', 'admin.site-content.offices*');

    $menu = [
        null => [
            ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'active' => Route::is('admin.dashboard')],
        ],
        'Rates' => [
            ['route' => 'admin.metal-rates.index', 'label' => 'Live Rates', 'icon' => 'fa-coins', 'active' => Route::is('admin.metal-rates*')],
            ['route' => 'admin.cities.index',      'label' => 'Cities',     'icon' => 'fa-city',  'active' => Route::is('admin.cities.*')],
        ],
        'Website' => [
            ['route' => 'admin.site-content.about',        'label' => 'About',     'icon' => 'fa-circle-info',  'active' => Route::is('admin.site-content.about*')],
            ['route' => 'admin.site-content.services',     'label' => 'Services',  'icon' => 'fa-gem',          'active' => Route::is('admin.site-content.services*')],
            ['route' => 'admin.site-content.offices',      'label' => 'Offices',   'icon' => 'fa-building',     'active' => $officesActive],
            ['route' => 'admin.site-content.gallery',      'label' => 'Gallery',   'icon' => 'fa-images',       'active' => Route::is('admin.site-content.gallery*')],
            ['route' => 'admin.site-content.testimonials', 'label' => 'Videos',    'icon' => 'fa-circle-play',  'active' => Route::is('admin.site-content.testimonials*')],
            ['route' => 'admin.site-content.blogs',        'label' => 'News',      'icon' => 'fa-newspaper',    'active' => Route::is('admin.site-content.blogs*')],
            ['route' => 'admin.knowledge.index',           'label' => 'Knowledge', 'icon' => 'fa-book-open',    'active' => Route::is('admin.knowledge.*')],
            ['route' => 'admin.pages.index',               'label' => 'Pages',     'icon' => 'fa-file-lines',   'active' => Route::is('admin.pages.*', 'admin.pages.sections.*')],
        ],
        'Enquiries' => [
            ['route' => 'admin.site-content.contact-messages', 'label' => 'Enquiries', 'icon' => 'fa-envelope-open-text', 'active' => Route::is('admin.site-content.contact-messages*'), 'badge' => $unreadCount],
        ],
        'Settings' => [
            ['route' => 'admin.bank-accounts.index',  'label' => 'Bank Details',   'icon' => 'fa-building-columns', 'active' => Route::is('admin.bank-accounts*')],
            ['route' => 'admin.footer.index',         'label' => 'Footer',         'icon' => 'fa-table-columns',    'active' => Route::is('admin.footer.*')],
            ['route' => 'admin.site-content.sliders', 'label' => 'Sliders',        'icon' => 'fa-panorama',         'active' => Route::is('admin.site-content.sliders*')],
            ['route' => 'admin.site-content.cta',     'label' => 'Call To Action', 'icon' => 'fa-bullhorn',         'active' => Route::is('admin.site-content.cta*')],
            ['route' => 'admin.site-content.settings','label' => 'Site Settings',  'icon' => 'fa-sliders',          'active' => $settingsActive],
        ],
    ];
@endphp

<nav class="sidebar-menu" aria-label="Admin menu">
    @foreach($menu as $heading => $items)
        @php $items = array_filter($items, fn ($item) => Route::has($item['route'])); @endphp
        @continue(empty($items))

        <div class="menu-group">
            @if($heading)
                <div class="menu-heading">{{ $heading }}</div>
            @endif
            @foreach($items as $item)
                <div class="menu-item">
                    <a href="{{ route($item['route']) }}" class="menu-link {{ $item['active'] ? 'active' : '' }}" @if($item['active']) aria-current="page" @endif>
                        <i class="fas {{ $item['icon'] }}" aria-hidden="true"></i>
                        <span>{{ $item['label'] }}</span>
                        @if(!empty($item['badge']))
                            <span class="menu-badge" title="{{ $item['badge'] }} unread">{{ $item['badge'] > 99 ? '99+' : $item['badge'] }}</span>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    @endforeach
</nav>
