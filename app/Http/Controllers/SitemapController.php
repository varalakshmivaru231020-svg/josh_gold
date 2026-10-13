<?php

namespace App\Http\Controllers;

use App\Support\SiteMap;

class SitemapController extends Controller
{
    public function index()
    {
        return view('sitemap', ['groups' => SiteMap::groups()]);
    }

    public function xml()
    {
        return response()
            ->view('sitemap-xml', ['urls' => SiteMap::urls()])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
