@extends('admin.layouts.app')

@section('title', 'Edit Page')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Page</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
        <li class="breadcrumb-item active">Edit Page</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Page
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Page Title *</label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ $page->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content *</label>
                            <textarea id="content" name="content"
                                      class="form-control" rows="8" required>{!! $page->content !!}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Banner Title</label>
                            <input type="text" name="banner_title" class="form-control"
                                   value="{{ $page->banner_title }}" placeholder="Leave empty to use page title">
                            <small class="text-muted">Displayed on the page banner</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Image</label>
                            @if($page->banner_image)
                            <div class="mb-2">
                                <img src="{{ versioned_asset('storage/' . $page->banner_image) }}" alt="Banner" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                            @endif
                            <input type="file" name="banner_image" class="form-control" accept="image/*">
                            <small class="text-muted">Recommended: 1920x600px, Max 5MB</small>
                            <div id="bannerPreview" class="mt-2"></div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_published"
                                   {{ $page->is_published ? 'checked' : '' }}>
                            <label class="form-check-label">Publish Page</label>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="show_in_footer" id="show_in_footer" value="1"
                                   {{ old('show_in_footer', $page->show_in_footer) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_in_footer">Show a link in the website footer</label>
                            <div class="form-text">Handy for pages like Terms &amp; Conditions or Privacy Policy. Only published pages appear.</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Page</button>
                </div>

            </form>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-list me-1"></i>
                Page Sections (Page Builder)
            </div>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                <i class="fas fa-plus"></i> Add Section
            </button>
        </div>
        <div class="card-body">
            @if($page->sections->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Type</th>
                                <th>Heading</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sectionsTable">
                            @foreach($page->sections as $section)
                            <tr data-id="{{ $section->id }}">
                                <td>{{ $section->sort_order }}</td>
                                <td><span class="badge bg-info text-dark">{{ ucfirst($section->section_type) }}</span></td>
                                <td>{{ $section->heading }}</td>
                                <td>
                                    @if($section->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.pages.sections.edit', $section->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pages.sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Delete this section?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted">No sections added yet. Click "Add Section" to start building your page.</p>
            @endif
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.pages.sections.store', $page->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Section Type</label>
                        <select name="section_type" class="form-select" required>
                            <option value="visionary">Visionary (Standard Section)</option>
                            <option value="counters">Counters / Stats</option>
                            <option value="cta">CTA (Call to Action)</option>
                            <option value="team">Teams / Profiles</option>
                            <option value="vision_mission">Vision & Mission</option>
                            <option value="professional_profiles">Professional Profiles (Author/Filmmaker)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Heading</label>
                        <input type="text" name="heading" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subheading</label>
                        <input type="text" name="subheading" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Section</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $('#content').summernote({
        height: 300,
        toolbar: [
            ['style', ['bold','italic','underline','clear']],
            ['para', ['ul','ol','paragraph']],
            ['insert', ['link','picture','video']],
            ['view', ['fullscreen','codeview']]
        ]
    });

    // Banner image preview
    document.querySelector('input[name="banner_image"]').addEventListener('change', function(e) {
        const preview = document.getElementById('bannerPreview');
        preview.innerHTML = '';
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'max-width: 100%; border-radius: 8px; margin-top: 10px;';
                preview.appendChild(img);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush
@endsection
