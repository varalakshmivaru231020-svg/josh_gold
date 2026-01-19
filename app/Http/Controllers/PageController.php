<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Public: show a page by slug
    public function show(Page $page)
    {
        // if you want only published pages
        if (!$page->is_published) {
            abort(404);
        }

        // Return view with page
        return view('pages.show', compact('page'));
    }

    // Optional admin methods (index/create/store/edit/update,destroy) if needed:
    public function index()
    {
        $pages = Page::orderBy('created_at','desc')->paginate(15);
        return view('admin.pages.index', compact('pages'));
    }

    // ... add create/store/edit/update/destroy for CMS management as required
}
