@php
    use App\Models\Collection;

    $collection = $collection ?? null;
    $type       = old('type', $collection?->type ?? 'bullion');
    $metal      = old('metal', $collection?->metal ?? 'gold');
    $imageUrl   = $collection ? Collection::imageUrl($collection->image) : null;
    $gallery    = array_values(array_filter((array) ($collection?->gallery ?? []), 'is_string'));
    $removing   = (array) old('gallery_remove', []);
    $purityHelp = [
        'gold'   => implode(', ', Collection::purityOptions('gold')),
        'silver' => implode(', ', Collection::purityOptions('silver')),
    ];
@endphp

<div class="row g-4">
    {{-- ─── Left: text and pricing ───────────────────── --}}
    <div class="col-lg-8">
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="name" class="form-label fw-semibold">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $collection?->name) }}" maxlength="120" required placeholder="e.g. Gold coins">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label for="slug" class="form-label fw-semibold">Slug</label>
                <input type="text" class="form-control font-monospace @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $collection?->slug) }}" maxlength="120" required pattern="[a-z0-9]+(-[a-z0-9]+)*" data-slug-from="name">
                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-text mt-2 mb-3">
            The slug is filled in from the name; you can edit it. Page address: <code id="collectionPathPreview">/collections/{{ old('slug', $collection?->slug) ?: 'slug' }}</code>
            @if($collection)<br><span class="text-warning-emphasis">Changing the slug changes the page address; old links to it will stop working.</span>@endif
        </div>

        <div class="row g-3 mb-3">
            <div class="col-sm-4">
                <label for="type" class="form-label fw-semibold">Type</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                    @foreach(Collection::TYPES as $key => $label)
                        <option value="{{ $key }}" @selected($type === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-4">
                <label for="metal" class="form-label fw-semibold">Metal</label>
                <select class="form-select @error('metal') is-invalid @enderror" id="metal" name="metal" required>
                    @foreach(Collection::METALS as $key => $label)
                        <option value="{{ $key }}" @selected($metal === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('metal') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-4" data-show-for="bullion">
                <label for="form" class="form-label fw-semibold">Form</label>
                <select class="form-select @error('form') is-invalid @enderror" id="form" name="form">
                    <option value="">— Select —</option>
                    @foreach(Collection::FORMS as $key => $label)
                        <option value="{{ $key }}" @selected(old('form', $collection?->form) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('form') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="tagline" class="form-label fw-semibold">Tagline <span class="text-muted fw-normal">(optional)</span></label>
            <input type="text" class="form-control @error('tagline') is-invalid @enderror" id="tagline" name="tagline" value="{{ old('tagline', $collection?->tagline) }}" maxlength="160" placeholder="e.g. 1 g to 100 g, priced live">
            <div class="form-text">Short line under the name on the category tiles. Keep it to a few words.</div>
            @error('tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="intro" class="form-label fw-semibold">Intro <span class="text-muted fw-normal">(optional)</span></label>
            <textarea class="form-control @error('intro') is-invalid @enderror" id="intro" name="intro" rows="3" maxlength="2000">{{ old('intro', $collection?->intro) }}</textarea>
            <div class="form-text">One or two sentences under the page title. Also used as the search-engine description.</div>
            @error('intro') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="body" class="form-label fw-semibold">About text <span class="text-muted fw-normal">(optional)</span></label>
            <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="6" maxlength="20000">{{ old('body', $collection?->body) }}</textarea>
            <div class="form-text">Shown in an "About the collection" section. Leave a blank line to start a new paragraph.</div>
            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12" data-show-for="bullion">
                <label for="weights" class="form-label fw-semibold">Weights (grams)</label>
                <input type="text" class="form-control font-monospace @error('weights') is-invalid @enderror" id="weights" name="weights" value="{{ old('weights', $collection?->weights) }}" maxlength="255" placeholder="1, 2, 5, 10, 116.64 (10 tola)">
                <div class="form-text">Separate with commas. 1000 shows as 1 kg. Add a name in brackets if you like, e.g. <code>116.64 (10 tola)</code>.</div>
                @error('weights') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="purities" class="form-label fw-semibold">Purities</label>
                <input type="text" class="form-control font-monospace @error('purities') is-invalid @enderror" id="purities" name="purities" value="{{ old('purities', $collection?->purities) }}" maxlength="255" placeholder="24K, 22K">
                <div class="form-text">
                    Separate with commas; the first is shown first.
                    <span data-show-for="bullion">Each one gets a button that switches the prices.</span>
                    <span data-show-for="jewellery">Shown in the rate panel (blank: 22K and 18K).</span>
                    <br>Allowed for <span id="purityMetal">{{ $metal }}</span>: <code id="purityAllowed">{{ $purityHelp[$metal] ?? $purityHelp['gold'] }}</code>
                </div>
                @error('purities') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Gallery (jewellery pages) --}}
        <div class="border rounded-3 p-3" data-show-for="jewellery">
            <label class="form-label fw-semibold mb-1">Gallery</label>
            <div class="form-text mt-0 mb-2">Photos shown in a grid on jewellery pages. Up to 12; JPG, PNG, WebP or GIF, 5 MB each.</div>

            @if($gallery)
                <div class="row g-2 mb-3">
                    @foreach($gallery as $path)
                        @php $src = Collection::imageUrl($path); @endphp
                        <div class="col-6 col-sm-4 col-md-3">
                            <div class="border rounded-2 p-1 h-100 bg-light">
                                @if($src)
                                    <img src="{{ $src }}" alt="" class="w-100 rounded-1" style="aspect-ratio: 4 / 3; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center text-muted small rounded-1" style="aspect-ratio: 4 / 3;">File missing</div>
                                @endif
                                <div class="form-check small mt-1 mb-0">
                                    <input class="form-check-input" type="checkbox" name="gallery_remove[]" value="{{ $path }}" id="gallery_remove_{{ $loop->index }}" @checked(in_array($path, $removing, true))>
                                    <label class="form-check-label" for="gallery_remove_{{ $loop->index }}">Remove</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <input type="file" class="form-control @error('gallery_uploads') is-invalid @enderror @error('gallery_uploads.*') is-invalid @enderror" name="gallery_uploads[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
            @error('gallery_uploads') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @foreach($errors->get('gallery_uploads.*') as $messages)
                <div class="invalid-feedback d-block">{{ $messages[0] }}</div>
                @break
            @endforeach
            <div class="form-text">New photos are added after the current ones.</div>
        </div>
    </div>

    {{-- ─── Right: image and visibility ─────────────── --}}
    <div class="col-lg-4">
        <div class="mb-4">
            <label class="form-label fw-semibold">Main image</label>
            <div class="mb-2">
                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="" class="img-fluid rounded border w-100" style="aspect-ratio: 4 / 3; object-fit: cover;">
                @elseif($collection?->image)
                    <div class="alert alert-warning small mb-0">The current image file is missing; the page shows a stock photo instead.</div>
                @elseif($collection)
                    <img src="{{ $collection->image_url }}" alt="" class="img-fluid rounded border w-100" style="aspect-ratio: 4 / 3; object-fit: cover; opacity: .7;">
                    <div class="form-text">No image chosen; the page shows this stock photo.</div>
                @endif
            </div>
            @if($collection?->image)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1" @checked(old('remove_image'))>
                    <label class="form-check-label" for="remove_image">Remove current image (use a stock photo)</label>
                </div>
            @endif
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif">
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Used on the page hero, the price cards and the category tile. Landscape 4:3 works best (e.g. 1000 × 750). JPG, PNG, WebP or GIF, up to 5 MB.{{ $collection?->image ? ' Leave blank to keep the current image.' : '' }}</div>
        </div>

        <div class="mb-3">
            <label for="sort_order" class="form-label fw-semibold">Sort Order <span class="text-muted fw-normal">(optional)</span></label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $collection?->sort_order ?? ($nextSortOrder ?? 0)) }}">
            <div class="form-text">Lower numbers appear first. Blank counts as 0.</div>
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch mb-1">
            <input class="form-check-input" type="checkbox" role="switch" id="show_on_home" name="show_on_home" value="1" @checked(old('show_on_home', $collection?->show_on_home ?? true))>
            <label class="form-check-label" for="show_on_home">Show on the home page</label>
        </div>
        <div class="form-text mb-3">Adds a tile to "Shop by category".</div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $collection?->is_active ?? true))>
            <label class="form-check-label" for="is_active">Show on website</label>
        </div>
        <div class="form-text">When off, the page shows "not found" and the tile is hidden everywhere.</div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        // Same idea as Laravel's Str::slug: "Gold Coins & Bars" -> "gold-coins-and-bars"
        function slugify(text) {
            return text.normalize('NFD').replace(/[̀-ͯ]/g, '')
                .toLowerCase().replace(/&/g, ' and ')
                .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }

        var slug = document.getElementById('slug');
        var name = document.getElementById('name');
        var preview = document.getElementById('collectionPathPreview');
        // Fill in automatically while the slug is empty or still matches the name; stop once edited by hand
        var auto = slug.value === '' || slug.value === slugify(name.value);

        function updatePreview() { preview.textContent = '/collections/' + (slug.value || 'slug'); }
        name.addEventListener('input', function () {
            if (auto) { slug.value = slugify(name.value); updatePreview(); }
        });
        slug.addEventListener('input', function () { auto = slug.value === ''; updatePreview(); });
        slug.addEventListener('blur', function () { slug.value = slugify(slug.value); updatePreview(); });

        // Bullion fields (form, weights) vs jewellery fields (gallery)
        var type = document.getElementById('type');
        var metal = document.getElementById('metal');
        var allowed = @json($purityHelp);
        function toggle() {
            document.querySelectorAll('[data-show-for]').forEach(function (el) {
                el.hidden = el.getAttribute('data-show-for') !== type.value;
            });
            document.getElementById('form').required = type.value === 'bullion';
            document.getElementById('purityMetal').textContent = metal.value;
            document.getElementById('purityAllowed').textContent = allowed[metal.value] || '';
        }
        type.addEventListener('change', toggle);
        metal.addEventListener('change', toggle);
        toggle();
    })();
</script>
@endpush
