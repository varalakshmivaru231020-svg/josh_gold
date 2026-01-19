<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\AboutSection;
use App\Models\FeaturedBook;
use App\Models\TestimonialVideo;
use App\Models\OrderFlowStep;
use App\Models\WhatsappFlowStep;
use App\Models\PromoBanner;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Blog;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteContentController extends Controller
{
    // ==================== SLIDERS ====================
    public function sliders()
    {
        $sliders = Slider::ordered()->paginate(10);
        return view('admin.site-content.sliders.index', compact('sliders'));
    }

    public function createSlider()
    {
        return view('admin.site-content.sliders.create');
    }

    public function storeSlider(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        Slider::create($data);

        return redirect()->route('admin.site-content.sliders')
            ->with('success', 'Slider created successfully.');
    }

    public function editSlider($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.site-content.sliders.edit', compact('slider'));
    }

    public function updateSlider(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $slider = Slider::findOrFail($id);
        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.site-content.sliders')
            ->with('success', 'Slider updated successfully.');
    }

    public function deleteSlider($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();

        return redirect()->route('admin.site-content.sliders')
            ->with('success', 'Slider deleted successfully.');
    }

    // ==================== ABOUT SECTION ====================
    public function aboutSection()
    {
        $about = AboutSection::first();
        return view('admin.site-content.about.edit', compact('about'));
    }

    public function updateAboutSection(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'years_experience' => 'nullable|string|max:50',
        ]);

        $about = AboutSection::first();
        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($about && $about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $data['image'] = $request->file('image')->store('about', 'public');
        }

        if ($about) {
            $about->update($data);
        } else {
            AboutSection::create($data);
        }

        return redirect()->route('admin.site-content.about')
            ->with('success', 'About section updated successfully.');
    }

    // ==================== FEATURED BOOK ====================
    public function featuredBook()
    {
        $book = FeaturedBook::first();
        $products = Product::where('is_active', true)->get();
        return view('admin.site-content.featured-book.edit', compact('book', 'products'));
    }

    public function updateFeaturedBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'book_name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'price' => 'nullable|numeric|min:0',
            'button_text' => 'nullable|string|max:100',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $book = FeaturedBook::first();
        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        // Set button link based on product_id
        if ($request->product_id) {
            $data['button_link'] = route('products.show', $request->product_id);
        }

        if ($request->hasFile('image')) {
            if ($book && $book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $data['image'] = $request->file('image')->store('featured-book', 'public');
        }

        if ($book) {
            $book->update($data);
        } else {
            FeaturedBook::create($data);
        }

        return redirect()->route('admin.site-content.featured-book')
            ->with('success', 'Featured book updated successfully.');
    }

    // ==================== TESTIMONIAL VIDEOS ====================
    public function testimonialVideos()
    {
        $videos = TestimonialVideo::ordered()->paginate(10);
        return view('admin.site-content.testimonials.index', compact('videos'));
    }

    public function createTestimonialVideo()
    {
        return view('admin.site-content.testimonials.create');
    }

    public function storeTestimonialVideo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author_name' => 'nullable|string|max:255',
            'author_designation' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except('thumbnail');
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('testimonials', 'public');
        }

        TestimonialVideo::create($data);

        return redirect()->route('admin.site-content.testimonials')
            ->with('success', 'Testimonial video added successfully.');
    }

    public function editTestimonialVideo($id)
    {
        $video = TestimonialVideo::findOrFail($id);
        return view('admin.site-content.testimonials.edit', compact('video'));
    }

    public function updateTestimonialVideo(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author_name' => 'nullable|string|max:255',
            'author_designation' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $video = TestimonialVideo::findOrFail($id);
        $data = $request->except('thumbnail');
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('testimonials', 'public');
        }

        $video->update($data);

        return redirect()->route('admin.site-content.testimonials')
            ->with('success', 'Testimonial video updated successfully.');
    }

    public function deleteTestimonialVideo($id)
    {
        $video = TestimonialVideo::findOrFail($id);
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        $video->delete();

        return redirect()->route('admin.site-content.testimonials')
            ->with('success', 'Testimonial video deleted successfully.');
    }

    // ==================== ORDER FLOW STEPS ====================
    public function orderFlow()
    {
        $steps = OrderFlowStep::ordered()->get();
        return view('admin.site-content.order-flow.index', compact('steps'));
    }

    public function createOrderFlowStep()
    {
        return view('admin.site-content.order-flow.create');
    }

    public function storeOrderFlowStep(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:100',
            'step_number' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        OrderFlowStep::create($data);

        return redirect()->route('admin.site-content.order-flow')
            ->with('success', 'Order flow step added successfully.');
    }

    public function editOrderFlowStep($id)
    {
        $step = OrderFlowStep::findOrFail($id);
        return view('admin.site-content.order-flow.edit', compact('step'));
    }

    public function updateOrderFlowStep(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:100',
            'step_number' => 'required|integer|min:1',
        ]);

        $step = OrderFlowStep::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $step->update($data);

        return redirect()->route('admin.site-content.order-flow')
            ->with('success', 'Order flow step updated successfully.');
    }

    public function deleteOrderFlowStep($id)
    {
        OrderFlowStep::findOrFail($id)->delete();
        return redirect()->route('admin.site-content.order-flow')
            ->with('success', 'Order flow step deleted successfully.');
    }

    // ==================== WHATSAPP FLOW STEPS ====================
    public function whatsappFlow()
    {
        $steps = WhatsappFlowStep::ordered()->get();
        return view('admin.site-content.whatsapp-flow.index', compact('steps'));
    }

    public function createWhatsappFlowStep()
    {
        return view('admin.site-content.whatsapp-flow.create');
    }

    public function storeWhatsappFlowStep(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:100',
            'step_number' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        WhatsappFlowStep::create($data);

        return redirect()->route('admin.site-content.whatsapp-flow')
            ->with('success', 'WhatsApp flow step added successfully.');
    }

    public function editWhatsappFlowStep($id)
    {
        $step = WhatsappFlowStep::findOrFail($id);
        return view('admin.site-content.whatsapp-flow.edit', compact('step'));
    }

    public function updateWhatsappFlowStep(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:100',
            'step_number' => 'required|integer|min:1',
        ]);

        $step = WhatsappFlowStep::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $step->update($data);

        return redirect()->route('admin.site-content.whatsapp-flow')
            ->with('success', 'WhatsApp flow step updated successfully.');
    }

    public function deleteWhatsappFlowStep($id)
    {
        WhatsappFlowStep::findOrFail($id)->delete();
        return redirect()->route('admin.site-content.whatsapp-flow')
            ->with('success', 'WhatsApp flow step deleted successfully.');
    }

    // ==================== PROMO BANNERS ====================
    public function promoBanners()
    {
        $banners = PromoBanner::paginate(10);
        return view('admin.site-content.promo-banners.index', compact('banners'));
    }

    public function createPromoBanner()
    {
        return view('admin.site-content.promo-banners.create');
    }

    public function storePromoBanner(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'position' => 'required|in:bottom,middle',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promo-banners', 'public');
        }

        PromoBanner::create($data);

        return redirect()->route('admin.site-content.promo-banners')
            ->with('success', 'Promo banner created successfully.');
    }

    public function editPromoBanner($id)
    {
        $banner = PromoBanner::findOrFail($id);
        return view('admin.site-content.promo-banners.edit', compact('banner'));
    }

    public function updatePromoBanner(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'position' => 'required|in:bottom,middle',
        ]);

        $banner = PromoBanner::findOrFail($id);
        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('promo-banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.site-content.promo-banners')
            ->with('success', 'Promo banner updated successfully.');
    }

    public function deletePromoBanner($id)
    {
        $banner = PromoBanner::findOrFail($id);
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return redirect()->route('admin.site-content.promo-banners')
            ->with('success', 'Promo banner deleted successfully.');
    }

    // ==================== SITE SETTINGS ====================
    public function siteSettings()
    {
        $logo = SiteSetting::get('site_logo');
        return view('admin.site-content.settings.index', compact('logo'));
    }

    public function updateSiteSettings(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            $oldLogo = SiteSetting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('site_logo')->store('settings', 'public');
            SiteSetting::set('site_logo', $path, 'image');
        }

        return redirect()->route('admin.site-content.settings')
            ->with('success', 'Site settings updated successfully.');
    }

    // ==================== BLOGS ====================
    public function blogs()
    {
        $blogs = Blog::orderByDesc('created_at')->paginate(10);
        return view('admin.site-content.blogs.index', compact('blogs'));
    }

    public function createBlog()
    {
        return view('admin.site-content.blogs.create');
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.site-content.blogs')
            ->with('success', 'Blog post created successfully.');
    }

    public function editBlog($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.site-content.blogs.edit', compact('blog'));
    }

    public function updateBlog(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'sort_order' => 'nullable|integer',
        ]);

        $blog = Blog::findOrFail($id);
        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.site-content.blogs')
            ->with('success', 'Blog post updated successfully.');
    }

    public function deleteBlog($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();

        return redirect()->route('admin.site-content.blogs')
            ->with('success', 'Blog post deleted successfully.');
    }

    // ==================== CONTACT SETTINGS ====================
    public function contactSettings()
    {
        $settings = [
            'contact_address' => SiteSetting::get('contact_address'),
            'contact_phone' => SiteSetting::get('contact_phone'),
            'contact_email' => SiteSetting::get('contact_email'),
            'contact_whatsapp' => SiteSetting::get('contact_whatsapp'),
            'contact_map_embed' => SiteSetting::get('contact_map_embed'),
            'contact_working_hours' => SiteSetting::get('contact_working_hours'),
        ];
        return view('admin.site-content.contact.settings', compact('settings'));
    }

    public function updateContactSettings(Request $request)
    {
        $request->validate([
            'contact_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:100',
            'contact_whatsapp' => 'nullable|string|max:20',
            'contact_map_embed' => 'nullable|string',
            'contact_working_hours' => 'nullable|string|max:255',
        ]);

        SiteSetting::set('contact_address', $request->contact_address);
        SiteSetting::set('contact_phone', $request->contact_phone);
        SiteSetting::set('contact_email', $request->contact_email);
        SiteSetting::set('contact_whatsapp', $request->contact_whatsapp);
        SiteSetting::set('contact_map_embed', $request->contact_map_embed);
        SiteSetting::set('contact_working_hours', $request->contact_working_hours);

        return redirect()->route('admin.site-content.contact-settings')
            ->with('success', 'Contact settings updated successfully.');
    }

    // ==================== CONTACT MESSAGES ====================
    public function contactMessages()
    {
        $messages = ContactMessage::orderByDesc('created_at')->paginate(20);
        return view('admin.site-content.contact.messages', compact('messages'));
    }

    public function viewContactMessage($id)
    {
        $message = ContactMessage::findOrFail($id);
        if ($message->status === 'unread') {
            $message->markAsRead();
        }
        return view('admin.site-content.contact.view-message', compact('message'));
    }

    public function deleteContactMessage($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return redirect()->route('admin.site-content.contact-messages')
            ->with('success', 'Message deleted successfully.');
    }
}
