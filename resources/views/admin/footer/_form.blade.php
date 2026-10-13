@php $link = $link ?? null; @endphp

<div class="row g-4">
    <div class="col-md-7">
        <div class="mb-3">
            <label for="label" class="form-label fw-semibold">Label</label>
            <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label', $link?->label) }}" maxlength="255" required placeholder="e.g. Gold rate today">
            @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="url" class="form-label fw-semibold">Link address</label>
            <input type="text" class="form-control font-monospace @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $link?->url) }}" maxlength="500" required placeholder="/gold-rate-today">
            <div class="form-text">A page of this website as a path starting with <code>/</code> (e.g. <code>/gold-rate-today</code>), or a full URL starting with <code>https://</code>. Links to this website are saved as paths so they keep working on any domain.</div>
            @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="open_in_new_tab" name="open_in_new_tab" value="1" {{ old('open_in_new_tab', $link?->open_in_new_tab ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="open_in_new_tab">Open in a new tab</label>
        </div>
    </div>

    <div class="col-md-5">
        <div class="mb-3">
            <label for="column_key" class="form-label fw-semibold">Column</label>
            <select class="form-select @error('column_key') is-invalid @enderror" id="column_key" name="column_key" required>
                @foreach($columns as $key => $heading)
                <option value="{{ $key }}" @selected(old('column_key', $link?->column_key ?? ($column ?? null)) === $key)>{{ $heading }}</option>
                @endforeach
            </select>
            @error('column_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="sort_order" class="form-label fw-semibold">Sort Order <span class="text-muted fw-normal">(optional)</span></label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $link?->sort_order ?? ($nextSortOrder ?? 0)) }}">
            <div class="form-text">Lower numbers appear first in the column. Blank counts as 0.</div>
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $link?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Show on website</label>
        </div>
    </div>
</div>
