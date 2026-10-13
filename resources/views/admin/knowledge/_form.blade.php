@php $article = $article ?? null; @endphp

<div class="row g-4">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $article?->title) }}" maxlength="255" required placeholder="e.g. Hallmarks and HUID">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="intro" class="form-label fw-semibold">Intro <span class="text-muted fw-normal">(optional)</span></label>
            <textarea class="form-control @error('intro') is-invalid @enderror" id="intro" name="intro" rows="3" maxlength="2000">{{ old('intro', $article?->intro) }}</textarea>
            <div class="form-text">One or two sentences shown under the title.</div>
            @error('intro') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="points" class="form-label fw-semibold">Points <span class="text-muted fw-normal">(optional)</span></label>
            <textarea class="form-control @error('points') is-invalid @enderror" id="points" name="points" rows="8">{{ old('points', $article?->points) }}</textarea>
            <div class="form-text">One point per line; each line becomes a bullet. Blank lines are ignored.</div>
            @error('points') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="tag" class="form-label fw-semibold">Tag</label>
            <input type="text" class="form-control @error('tag') is-invalid @enderror" id="tag" name="tag" value="{{ old('tag', $article?->tag) }}" maxlength="60" required placeholder="e.g. Purity">
            <div class="form-text">Short label shown above the title.</div>
            @error('tag') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="sort_order" class="form-label fw-semibold">Sort Order <span class="text-muted fw-normal">(optional)</span></label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $article?->sort_order ?? ($nextSortOrder ?? 0)) }}">
            <div class="form-text">Lower numbers appear first. Blank counts as 0.</div>
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $article?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Show on website</label>
        </div>
    </div>
</div>
