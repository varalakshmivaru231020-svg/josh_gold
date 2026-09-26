@extends('admin.layouts.app')

@section('title', 'Pages')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Pages</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Pages</li>
    </ol>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Page
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-file-alt me-1"></i> Pages List
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td>{{ $page->slug }}</td>
                        <td>
                            @if($page->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-danger">Unpublished</span>
                            @endif
                            @if($page->is_published && $page->show_in_footer)
                                <span class="badge bg-warning text-dark ms-1" title="Linked from the website footer"><i class="fas fa-link me-1"></i>Footer</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.pages.destroy', $page->id) }}" 
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this page?')" 
                                        class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection
