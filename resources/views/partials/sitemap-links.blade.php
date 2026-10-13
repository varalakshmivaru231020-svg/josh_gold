{{-- Grouped links to every page. Expects: $groups (App\Support\SiteMap::groups()) --}}
<div class="jb-sitemap">
    @foreach($groups as $group => $links)
        <div class="jb-sitemap__group">
            <h3>{{ $group }}</h3>
            <ul>
                @foreach($links as [$label, $url])
                    <li><a href="{{ $url }}">{{ $label }}</a></li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
