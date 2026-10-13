<?php

namespace App\Http\Controllers;

use App\Support\SiteMap;

class SitemapController extends Controller
{
    public function index()
    {
        return view('sitemap', ['groups' => SiteMap::groups()]);
    }

    // Built as a string rather than a Blade view: servers with short_open_tag enabled
    // would read the "<?xml" declaration in a template as PHP.
    public function xml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach (SiteMap::urls() as $url) {
            $xml .= '    <url><loc>' . htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</loc></url>' . "\n";
        }
        $xml .= '</urlset>' . "\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
