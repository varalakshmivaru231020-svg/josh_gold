@extends('admin.layouts.app')

@section('title', 'Edit Page Section')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Section: {{ ucfirst($section->section_type) }}</h6>
                    <a href="{{ route('admin.pages.edit', $section->page_id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Page
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pages.sections.update', $section->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        @if($section->section_type === 'about_intro')
                            <div class="mb-4 p-3 bg-light border rounded">
                                <label class="form-label font-weight-bold card-text text-primary"><i class="fas fa-image me-2"></i> Section Image</label>
                                @if(isset($section->cards[0]['image']))
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $section->cards[0]['image']) }}" alt="Current Image" style="max-width: 200px;" class="img-thumbnail d-block">
                                        <small class="text-muted">Current image</small>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control">
                                <small class="text-muted">Upload a professional image for the About Intro section.</small>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Heading</label>
                            <input type="text" name="heading" 
                                   class="form-control" 
                                   value="{{ old('heading', $section->heading) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subheading</label>
                            <input type="text" name="subheading" 
                                   class="form-control" 
                                   value="{{ old('subheading', $section->subheading) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content (Main Text)</label>
                            <textarea name="content" class="form-control" rows="5">{{ old('content', $section->content) }}</textarea>
                        </div>

                        @if($section->section_type === 'vision_mission')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">Vision Statement</label>
                                    <textarea name="cards[0][vision]" class="form-control" rows="8">{{ $section->cards[0]['vision'] ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">Mission Statement</label>
                                    <textarea name="cards[0][mission]" class="form-control" rows="8">{{ $section->cards[0]['mission'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @elseif($section->section_type === 'counters')
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> This section automatically displays data from the <strong>Counters / Stats</strong> module in the admin sidebar. You do not need to manage items here.
                            </div>
                        @elseif($section->section_type === 'professional_profiles')
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">Professional Profiles (Beyond the Bar)</h5>
                                @for($i = 0; $i < 3; $i++)
                                    @php $profile = $section->cards[$i] ?? []; @endphp
                                    <div class="card mb-3 border-left-primary h-100 py-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Role Title (e.g. Author)</label>
                                                    <input type="text" name="profile_items[{{$i}}][type]" class="form-control mb-2" value="{{ $profile['type'] ?? '' }}">
                                                    
                                                    <label class="form-label">Icon (FA Class)</label>
                                                    <input type="text" name="profile_items[{{$i}}][icon]" class="form-control mb-2" value="{{ $profile['icon'] ?? '' }}">

                                                    <label class="form-label">Profile Image</label>
                                                    @if(isset($profile['image']))
                                                        <div class="mb-2">
                                                            <img src="{{ asset('storage/' . $profile['image']) }}" class="img-thumbnail" style="height: 60px;">
                                                        </div>
                                                    @endif
                                                    <input type="file" name="profile_images[{{$i}}]" class="form-control">
                                                </div>
                                                <div class="col-md-9">
                                                    <label class="form-label">Description (Paragraph)</label>
                                                    <textarea name="profile_items[{{$i}}][description]" class="form-control" rows="6">{{ $profile['description'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        @elseif($section->section_type === 'about_intro')
                            {{-- Hide cards JSON for about_intro --}}
                        @else
                            <div class="mb-3">
                                <label class="form-label">Cards (JSON Data)</label>
                                <textarea name="cards" class="form-control font-monospace" rows="10">{{ old('cards', json_encode($section->cards, JSON_PRETTY_PRINT)) }}</textarea>
                                <small class="text-muted">Enter data in JSON format for multiple cards/items.</small>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $section->sort_order }}">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $section->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Active</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update Section</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
