@php $city = $city ?? null; @endphp

<div class="row g-4">
    <div class="col-md-7">
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="name" class="form-label fw-semibold">City name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $city?->name) }}" maxlength="100" required placeholder="e.g. Mumbai">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label for="state" class="form-label fw-semibold">State</label>
                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $city?->state) }}" maxlength="100" required placeholder="e.g. Maharashtra">
                @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label for="slug" class="form-label fw-semibold">City slug</label>
                <input type="text" class="form-control font-monospace @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $city?->slug) }}" maxlength="100" required pattern="[a-z0-9]+(-[a-z0-9]+)*" data-slug-from="name">
                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label for="state_slug" class="form-label fw-semibold">State slug</label>
                <input type="text" class="form-control font-monospace @error('state_slug') is-invalid @enderror" id="state_slug" name="state_slug" value="{{ old('state_slug', $city?->state_slug) }}" maxlength="100" required pattern="[a-z0-9]+(-[a-z0-9]+)*" data-slug-from="state">
                @error('state_slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-text mt-2">
            Slugs are filled in from the names; you can edit them. Lowercase letters, numbers and hyphens only.
            Page address: <code id="cityPathPreview">/gold-rate/{{ old('state_slug', $city?->state_slug) ?: 'state' }}/{{ old('slug', $city?->slug) ?: 'city' }}</code>
            @if($city)<br><span class="text-warning-emphasis">Changing a slug changes the page address; old links to it will stop working.</span>@endif
        </div>
    </div>

    <div class="col-md-5">
        <div class="mb-3">
            <label for="sort_order" class="form-label fw-semibold">Sort Order <span class="text-muted fw-normal">(optional)</span></label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $city?->sort_order ?? ($nextSortOrder ?? 0)) }}">
            <div class="form-text">Lower numbers appear first. Blank counts as 0.</div>
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" role="switch" id="show_in_menu" name="show_in_menu" value="1" {{ old('show_in_menu', $city?->show_in_menu ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="show_in_menu">Show in the header menu</label>
        </div>
        <div class="form-text mb-3">Keep this to two or three cities so the header stays on one line.</div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $city?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Show on website</label>
        </div>
        <div class="form-text">When off, the city's rate page is hidden and its address shows "not found".</div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        // Same idea as Laravel's Str::slug: "Tamil Nadu" -> "tamil-nadu"
        function slugify(text) {
            return text.normalize('NFD').replace(/[̀-ͯ]/g, '')
                .toLowerCase().replace(/&/g, ' and ')
                .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }

        var preview = document.getElementById('cityPathPreview');
        var slugs = Array.prototype.slice.call(document.querySelectorAll('[data-slug-from]'));

        function updatePreview() {
            var state = document.getElementById('state_slug').value || 'state';
            var city = document.getElementById('slug').value || 'city';
            preview.textContent = '/gold-rate/' + state + '/' + city;
        }

        slugs.forEach(function (slug) {
            var source = document.getElementById(slug.getAttribute('data-slug-from'));
            // Fill in automatically while the slug is empty or still matches its name; stop once edited by hand
            var auto = slug.value === '' || slug.value === slugify(source.value);

            source.addEventListener('input', function () {
                if (auto) { slug.value = slugify(source.value); updatePreview(); }
            });
            slug.addEventListener('input', function () {
                auto = slug.value === '';
                updatePreview();
            });
            slug.addEventListener('blur', function () {
                slug.value = slugify(slug.value);
                updatePreview();
            });
        });
    })();
</script>
@endpush
