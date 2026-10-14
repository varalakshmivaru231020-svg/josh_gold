<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Office;
use App\Models\SiteSetting;
use App\Models\ContactMessage;
use App\Models\GalleryPhoto;
use App\Models\BankAccount;
use App\Models\MetalRate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Only what the home page shows: services, offices (contact address fallback) and the latest rate
        $services = Service::active()->ordered()->get();
        $offices = Office::active()->ordered()->get();

        // Latest published rate, for the rate strip and the price-by-weight cards
        $rate = MetalRate::currentRate();

        return view('home', compact('rate', 'services', 'offices'));
    }

    public function gallery()
    {
        $galleryPhotos = GalleryPhoto::active()->ordered()->get();
        return view('gallery', compact('galleryPhotos'));
    }

    public function aboutUs()
    {
        $about = AboutSection::active()->first();
        return view('about-us', compact('about'));
    }

    public function services()
    {
        $services = Service::active()->ordered()->get();
        return view('services.index', compact('services'));
    }

    public function bankDetails()
    {
        $accounts = BankAccount::active()->ordered()->get();
        return view('bank-details', compact('accounts'));
    }

    public function offices()
    {
        $offices = Office::active()->ordered()->get();
        return view('offices.index', compact('offices'));
    }

    // Updates page (/updates): a wide lead card plus a grid of three columns, so 10 a page fills the grid
    public function blogIndex()
    {
        $blogs = Blog::active()->ordered()->paginate(10);
        return view('blog.index', compact('blogs'));
    }

    public function blogShow($slug)
    {
        $blog = Blog::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Sidebar: recent posts
        $recentBlogs = Blog::active()
            ->where('id', '!=', $blog->id)
            ->ordered()
            ->take(5)
            ->get();

        // End-of-article: related articles
        $relatedBlogs = Blog::active()
            ->where('id', '!=', $blog->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Previous / Next navigation
        $previousBlog = Blog::active()
            ->where('id', '<', $blog->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextBlog = Blog::active()
            ->where('id', '>', $blog->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('blog.show', compact('blog', 'recentBlogs', 'relatedBlogs', 'previousBlog', 'nextBlog'));
    }

    public function contactPage()
    {
        $contact = [
            'address' => SiteSetting::get('contact_address'),
            'phone' => SiteSetting::get('contact_phone'),
            'email' => SiteSetting::get('contact_email'),
            'email_2' => SiteSetting::get('contact_email_2'),
            'whatsapp' => SiteSetting::get('contact_whatsapp'),
            'map_embed' => SiteSetting::get('contact_map_embed'),
            'working_hours' => SiteSetting::get('contact_working_hours'),
        ];
        return view('contact', compact('contact'));
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create($request->only(['name', 'email', 'phone', 'subject', 'message']));

        return redirect()->route('contact')->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
