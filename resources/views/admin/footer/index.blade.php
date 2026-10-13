@extends('admin.layouts.app')

@section('title', 'Footer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Footer</h1>
        <p class="text-muted mb-0">Links, column headings and text in the footer at the bottom of every page.</p>
    </div>
    <a href="{{ route('admin.site-content.social-links') }}" class="btn btn-outline-primary">
        <i class="fas fa-share-alt me-2"></i>Social icons
    </a>
</div>

{{-- The order inputs in the tables below belong to this form (a form can't wrap the per-row delete forms) --}}
<form id="footerOrderForm" action="{{ route('admin.footer.links.order') }}" method="POST" class="d-none">
    @csrf
    @method('PUT')
</form>

<div class="row g-4">
    <div class="col-xl-8">
        @foreach($columns as $key => $column)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>{{ $column['heading'] }} <span class="text-muted fw-normal small">column</span></h5>
                <a href="{{ route('admin.footer.links.create', ['column' => $key]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Add link
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Label</th>
                                <th>Link</th>
                                <th width="100">Order</th>
                                <th width="100">Status</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($column['links'] as $link)
                            <tr>
                                <td>
                                    <strong>{{ $link->label }}</strong>
                                    @if($link->open_in_new_tab)<i class="fas fa-external-link-alt text-muted small ms-1" title="Opens in a new tab"></i>@endif
                                </td>
                                <td class="text-break"><a href="{{ $link->href }}" target="_blank" rel="noopener"><code>{{ $link->url }}</code></a></td>
                                <td>
                                    <input type="number" form="footerOrderForm" name="order[{{ $link->id }}]" value="{{ $link->sort_order }}" class="form-control form-control-sm" style="width: 80px;" aria-label="Order of {{ $link->label }}">
                                </td>
                                <td>
                                    @if($link->is_active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.footer.links.edit', $link) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.footer.links.destroy', $link) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this link?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No links in this column. It is hidden on the website until you add one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach

        @if(collect($columns)->sum(fn ($column) => $column['links']->count()) > 0)
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Lower numbers appear first in their column. Change the numbers, then save.</span>
            <button type="submit" form="footerOrderForm" class="btn btn-outline-primary">
                <i class="fas fa-sort-amount-down me-2"></i>Save order
            </button>
        </div>
        @endif
    </div>

    <div class="col-xl-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-font me-2"></i>Footer text</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.footer.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @foreach($columns as $key => $column)
                    <div class="mb-3">
                        <label for="footer_heading_{{ $key }}" class="form-label fw-semibold">Heading: {{ $column['default'] }} column</label>
                        <input type="text" class="form-control @error('footer_heading_' . $key) is-invalid @enderror" id="footer_heading_{{ $key }}" name="footer_heading_{{ $key }}" value="{{ old('footer_heading_' . $key, $column['heading']) }}" maxlength="60" required>
                        @error('footer_heading_' . $key) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    @endforeach

                    <div class="mb-3">
                        <label for="footer_about" class="form-label fw-semibold">About text <span class="text-muted fw-normal">(under the logo)</span></label>
                        <textarea class="form-control @error('footer_about') is-invalid @enderror" id="footer_about" name="footer_about" rows="3" maxlength="1000">{{ old('footer_about', $about) }}</textarea>
                        @error('footer_about') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="footer_note" class="form-label fw-semibold">Bottom note <span class="text-muted fw-normal">(disclaimer)</span></label>
                        <textarea class="form-control @error('footer_note') is-invalid @enderror" id="footer_note" name="footer_note" rows="5" maxlength="2000">{{ old('footer_note', $note) }}</textarea>
                        @error('footer_note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Leave the about text or the note empty to hide it.</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save text
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-info">
            <div class="card-header bg-info text-white py-3">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Also in the footer</h6>
            </div>
            <div class="card-body small">
                <ul class="mb-0 ps-3">
                    <li class="mb-2"><strong>Social icons</strong> under the logo: <a href="{{ route('admin.site-content.social-links') }}">manage social links</a>.</li>
                    <li class="mb-2"><strong>Contact</strong> column: phone, email, WhatsApp, hours and address from <a href="{{ route('admin.site-content.settings', ['tab' => 'contact']) }}">Site Settings → Contact</a>.</li>
                    <li class="mb-2"><strong>Bottom links</strong>: published <a href="{{ route('admin.pages.index') }}">Pages</a> marked "show in footer", followed by the Sitemap.</li>
                    <li>Link addresses can be a page path such as <code>/gold-rate-today</code> (works on any domain) or a full URL such as <code>https://…</code>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
