<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slider;
use App\Models\AboutSection;
use App\Models\FeaturedBook;
use App\Models\TestimonialVideo;
use App\Models\OrderFlowStep;
use App\Models\WhatsappFlowStep;
use App\Models\PromoBanner;
use App\Models\Blog;
use App\Models\SiteSetting;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all dynamic content
        $sliders = Slider::active()->ordered()->get();
        $about = AboutSection::active()->first();
        $featuredBook = FeaturedBook::active()->first();
        $testimonialVideos = TestimonialVideo::active()->featured()->ordered()->take(10)->get();
        $orderFlowSteps = OrderFlowStep::active()->ordered()->get();
        $whatsappFlowSteps = WhatsappFlowStep::active()->ordered()->get();
        $promoBanner = PromoBanner::active()->position('bottom')->first();
        $blogs = Blog::active()->ordered()->take(3)->get();

        return view('home', compact(
            'sliders',
            'about',
            'featuredBook',
            'testimonialVideos',
            'orderFlowSteps',
            'whatsappFlowSteps',
            'promoBanner',
            'blogs'
        ));
    }

    public function testimonials()
    {
        $testimonialVideos = TestimonialVideo::active()->ordered()->paginate(12);
        return view('testimonials', compact('testimonialVideos'));
    }

    public function blogIndex()
    {
        $blogs = Blog::active()->ordered()->paginate(9);
        return view('blog.index', compact('blogs'));
    }

    public function blogShow($slug)
    {
        $blog = Blog::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedBlogs = Blog::active()->where('id', '!=', $blog->id)->ordered()->take(3)->get();
        return view('blog.show', compact('blog', 'relatedBlogs'));
    }

    public function contactPage()
    {
        $contact = [
            'address' => SiteSetting::get('contact_address'),
            'phone' => SiteSetting::get('contact_phone'),
            'email' => SiteSetting::get('contact_email'),
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
