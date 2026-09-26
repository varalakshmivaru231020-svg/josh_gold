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
use App\Models\Service;
use App\Models\Stat;
use App\Models\Office;
use App\Models\SocialLink;
use App\Models\GalleryPhoto;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiteContentController extends Controller implements HasMiddleware
{
    /** A button link may be an http(s) URL, a site path, an #anchor, tel: or mailto:. */
    private const LINK_PATTERN = '#^(https?://|/|\#|tel:|mailto:)#i';

    // A blank Sort Order arrives as null, but the sort_order columns are NOT NULL: treat blank as 0.
    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                if ($request->exists('sort_order') && $request->input('sort_order') === null) {
                    $request->merge(['sort_order' => 0]);
                }

                return $next($request);
            },
        ];
    }

    // ==================== SLIDERS ====================
    public function sliders(Request $request)
    {
        // ?show= (not ?page=, which the paginator uses for the page number)
        $show   = (string) $request->query('show');
        $filter = array_key_exists($show, Slider::PAGES) ? $show : null;

        $sliders = Slider::when($filter, fn ($q) => $q->where('page', $filter))
            ->orderBy('page')->ordered()->paginate(15)->withQueryString();

        return view('admin.site-content.sliders.index', compact('sliders', 'filter'));
    }

    public function createSlider()
    {
        return view('admin.site-content.sliders.create');
    }

    public function storeSlider(Request $request)
    {
        $request->validate([
            'page' => ['required', Rule::in(array_keys(Slider::PAGES))],
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => ['nullable', 'string', 'max:255', 'regex:' . self::LINK_PATTERN],
            'sort_order' => 'nullable|integer',
        ], ['button_link.regex' => 'Enter a full link (https://…), a path starting with / (e.g. /contact-us), or #section.']);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        Slider::create($data);

        return redirect()->route('admin.site-content.sliders', ['show' => $data['page']])
            ->with('success', 'Slider created for ' . Slider::PAGES[$data['page']] . '.');
    }

    public function editSlider($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.site-content.sliders.edit', compact('slider'));
    }

    public function updateSlider(Request $request, $id)
    {
        $request->validate([
            'page' => ['required', Rule::in(array_keys(Slider::PAGES))],
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => ['nullable', 'string', 'max:255', 'regex:' . self::LINK_PATTERN],
            'sort_order' => 'nullable|integer',
        ], ['button_link.regex' => 'Enter a full link (https://…), a path starting with / (e.g. /contact-us), or #section.']);

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
            'title'            => 'nullable|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'content'          => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'hero_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $about = AboutSection::first();
        $data = $request->only(['title', 'subtitle', 'content']);
        $data['is_active'] = $request->has('is_active');

        // Handle Main Image (About Page)
        if ($request->hasFile('image')) {
            if ($about && $about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $data['image'] = $request->file('image')->store('about', 'public');
        }

        // Handle Hero Image
        if ($request->hasFile('hero_image')) {
            if ($about && $about->hero_image) {
                Storage::disk('public')->delete($about->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')->store('about/hero', 'public');
        }

        // "Remove" checkboxes
        foreach (['image' => 'about', 'hero_image' => 'about/hero'] as $field => $dir) {
            if ($request->boolean("remove_$field") && ! $request->hasFile($field) && $about && $about->$field) {
                Storage::disk('public')->delete($about->$field);
                $data[$field] = null;
            }
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
    /** One Site Settings page with three tabs: General (logo), Contact and Offices. */
    public function siteSettings(Request $request)
    {
        $tab = in_array($request->query('tab'), ['general', 'contact', 'offices'], true) ? $request->query('tab') : 'general';

        $logo = $tab === 'general' ? SiteSetting::get('site_logo') : null;
        $contact = $tab === 'contact' ? [
            'contact_address' => SiteSetting::get('contact_address'),
            'contact_phone' => SiteSetting::get('contact_phone'),
            'contact_email' => SiteSetting::get('contact_email'),
            'contact_whatsapp' => SiteSetting::get('contact_whatsapp'),
            'contact_map_embed' => SiteSetting::get('contact_map_embed'),
            'contact_working_hours' => SiteSetting::get('contact_working_hours'),
        ] : [];
        $offices = $tab === 'offices' ? Office::ordered()->get() : collect();

        return view('admin.site-content.settings.index', compact('tab', 'logo', 'contact', 'offices'));
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
    // Contact Settings now lives in the Contact tab of Site Settings; the old URL still works.
    public function contactSettings()
    {
        return redirect()->route('admin.site-content.settings', ['tab' => 'contact']);
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

        return redirect()->route('admin.site-content.settings', ['tab' => 'contact'])
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

    // ==================== SERVICES ====================
    public function services()
    {
        $services = Service::ordered()->paginate(10);
        return view('admin.site-content.services.index', compact('services'));
    }

    public function createService()
    {
        return view('admin.site-content.services.create');
    }

    public function storeService(Request $request)
    {
        $data = $this->validatedService($request);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('admin.site-content.services')
            ->with('success', 'Service added successfully.');
    }

    public function editService($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.site-content.services.edit', compact('service'));
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $data = $this->validatedService($request);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        } elseif ($request->boolean('remove_image') && $service->image) {
            Storage::disk('public')->delete($service->image);
            $data['image'] = null;
        }

        $service->update($data);

        return redirect()->route('admin.site-content.services')
            ->with('success', 'Service updated successfully.');
    }

    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();

        return redirect()->route('admin.site-content.services')
            ->with('success', 'Service deleted successfully.');
    }

    private function validatedService(Request $request): array
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'sort_order'  => 'nullable|integer',
        ]);

        unset($data['image']); // the upload is stored separately
        $data['sort_order'] = $data['sort_order'] ?? 0; // blank means 0 (the column is NOT NULL)

        return $data;
    }

    // ==================== OFFICES ====================
    // The Offices list now lives in the Offices tab of Site Settings; the old URL still works.
    public function offices()
    {
        return redirect()->route('admin.site-content.settings', ['tab' => 'offices']);
    }

    public function createOffice()
    {
        return view('admin.site-content.offices.create');
    }

    public function storeOffice(Request $request)
    {
        $request->validate([
            'title'      => 'nullable|string|max:255|required_without:address',
            'subtitle'   => 'nullable|string|max:255',
            'address'    => 'nullable|string|required_without:title',
            'phone'      => 'nullable|string|max:50',
            'mobile'     => 'nullable|string|max:50',
            'email'      => 'nullable|string|max:100',
            'website'    => 'nullable|string|max:255',
            'icon'       => 'nullable|string|max:100',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');
        $data['title'] = $data['title'] ?? ''; // title is optional; the column itself is NOT NULL

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('offices', 'public');
        }

        Office::create($data);

        return redirect()->route('admin.site-content.settings', ['tab' => 'offices'])
            ->with('success', 'Office added successfully.');
    }

    public function editOffice($id)
    {
        $office = Office::findOrFail($id);
        return view('admin.site-content.offices.edit', compact('office'));
    }

    public function updateOffice(Request $request, $id)
    {
        $request->validate([
            'title'      => 'nullable|string|max:255|required_without:address',
            'subtitle'   => 'nullable|string|max:255',
            'address'    => 'nullable|string|required_without:title',
            'phone'      => 'nullable|string|max:50',
            'mobile'     => 'nullable|string|max:50',
            'email'      => 'nullable|string|max:100',
            'website'    => 'nullable|string|max:255',
            'icon'       => 'nullable|string|max:100',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'remove_image' => 'nullable|boolean',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $office = Office::findOrFail($id);
        $data = $request->except('image', 'remove_image');
        $data['is_active'] = $request->has('is_active');
        $data['title'] = $data['title'] ?? ''; // title is optional; the column itself is NOT NULL

        if ($request->boolean('remove_image') && $office->image) {
            Storage::disk('public')->delete($office->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($office->image) Storage::disk('public')->delete($office->image);
            $data['image'] = $request->file('image')->store('offices', 'public');
        }

        $office->update($data);

        return redirect()->route('admin.site-content.settings', ['tab' => 'offices'])
            ->with('success', 'Office updated successfully.');
    }

    public function deleteOffice($id)
    {
        $office = Office::findOrFail($id);
        if ($office->image) Storage::disk('public')->delete($office->image);
        $office->delete();
        return redirect()->route('admin.site-content.settings', ['tab' => 'offices'])
            ->with('success', 'Office deleted successfully.');
    }

    // ==================== SOCIAL LINKS ====================
    public function socialLinks()
    {
        $links = SocialLink::ordered()->get();
        return view('admin.site-content.social-links.index', compact('links'));
    }

    public function createSocialLink()
    {
        return view('admin.site-content.social-links.create');
    }

    public function storeSocialLink(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|url',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        SocialLink::create($data);

        return redirect()->route('admin.site-content.social-links')
            ->with('success', 'Social link added successfully.');
    }

    public function editSocialLink($id)
    {
        $link = SocialLink::findOrFail($id);
        return view('admin.site-content.social-links.edit', compact('link'));
    }

    public function updateSocialLink(Request $request, $id)
    {
        $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|url',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $link = SocialLink::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $link->update($data);

        return redirect()->route('admin.site-content.social-links')
            ->with('success', 'Social link updated successfully.');
    }

    public function deleteSocialLink($id)
    {
        SocialLink::findOrFail($id)->delete();
        return redirect()->route('admin.site-content.social-links')
            ->with('success', 'Social link deleted successfully.');
    }

    // ==================== STATS ====================
    public function stats()
    {
        $stats = Stat::ordered()->get();
        return view('admin.site-content.stats.index', compact('stats'));
    }

    public function createStat()
    {
        return view('admin.site-content.stats.create');
    }

    public function storeStat(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'count' => 'required|integer',
            'suffix' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Stat::create($data);

        return redirect()->route('admin.site-content.stats')
            ->with('success', 'Stat added successfully.');
    }

    public function editStat($id)
    {
        $stat = Stat::findOrFail($id);
        return view('admin.site-content.stats.edit', compact('stat'));
    }

    public function updateStat(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'count' => 'required|integer',
            'suffix' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $stat = Stat::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $stat->update($data);

        return redirect()->route('admin.site-content.stats')
            ->with('success', 'Stat updated successfully.');
    }

    public function deleteStat($id)
    {
        Stat::findOrFail($id)->delete();
        return redirect()->route('admin.site-content.stats')
            ->with('success', 'Stat deleted successfully.');
    }

    // ==================== GALLERY PHOTOS ====================
    public function galleryPhotos()
    {
        $photos = GalleryPhoto::ordered()->paginate(20);
        return view('admin.site-content.gallery.index', compact('photos'));
    }

    public function createGalleryPhoto()
    {
        return view('admin.site-content.gallery.create');
    }

    public function storeGalleryPhoto(Request $request)
    {
        $request->validate([
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'title'      => 'nullable|string|max:255',
            'caption'    => 'nullable|string|max:255',
            'category'   => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');
        $data['image'] = $request->file('image')->store('gallery', 'public');

        GalleryPhoto::create($data);

        return redirect()->route('admin.site-content.gallery')
            ->with('success', 'Photo uploaded successfully.');
    }

    public function editGalleryPhoto($id)
    {
        $photo = GalleryPhoto::findOrFail($id);
        return view('admin.site-content.gallery.edit', compact('photo'));
    }

    public function updateGalleryPhoto(Request $request, $id)
    {
        $request->validate([
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'title'      => 'nullable|string|max:255',
            'caption'    => 'nullable|string|max:255',
            'category'   => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $photo = GalleryPhoto::findOrFail($id);
        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($photo->image);
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }

        $photo->update($data);

        return redirect()->route('admin.site-content.gallery')
            ->with('success', 'Photo updated successfully.');
    }

    public function deleteGalleryPhoto($id)
    {
        $photo = GalleryPhoto::findOrFail($id);
        Storage::disk('public')->delete($photo->image);
        $photo->delete();
        return redirect()->route('admin.site-content.gallery')
            ->with('success', 'Photo deleted successfully.');
    }

    // ==================== BEYOND THE BAR SECTION ====================
    public function beyondBar()
    {
        $settings = [
            'bb_eyebrow'       => SiteSetting::get('bb_eyebrow', 'BEYOND THE BAR'),
            'bb_title'         => SiteSetting::get('bb_title', 'Filmmaker, Journalist & Cultural Visionary'),
            'bb_image_top'     => SiteSetting::get('bb_image_top'),
            'bb_image_left'    => SiteSetting::get('bb_image_left'),
            'bb_image_right'   => SiteSetting::get('bb_image_right'),
            
            'bb_item1_title'   => SiteSetting::get('bb_item1_title', 'Filmmaker, Director & Lead Actor'),
            'bb_item1_desc'    => SiteSetting::get('bb_item1_desc', 'Produced, directed and acted in the Kannada film Swayam Krushi — transforming stories of farmers and the soil into powerful cinema that honours Karnataka\'s rural heritage.'),
            'bb_item1_icon'    => SiteSetting::get('bb_item1_icon', 'fas fa-clapperboard'),
            
            'bb_item2_title'   => SiteSetting::get('bb_item2_title', 'Senior Journalist & Chief Editor'),
            'bb_item2_desc'    => SiteSetting::get('bb_item2_desc', 'Served as Chief Editor of Mussanje (Kannada evening daily) and editorial head of V News 24 (Kannada TV channel) — a career built on truth, accountability and fearless reporting.'),
            'bb_item2_icon'    => SiteSetting::get('bb_item2_icon', 'fas fa-newspaper'),
            
            'bb_item3_title'   => SiteSetting::get('bb_item3_title', 'Prolific Kannada Author — 15+ Works'),
            'bb_item3_desc'    => SiteSetting::get('bb_item3_desc', 'From Swayam Krushi to Thanks Appa to State vs Abhiram and the 12-volume Dainandina Jeevanakkagi Kanunu series — his literary output is a gift to Karnataka\'s language and legal awareness.'),
            'bb_item3_icon'    => SiteSetting::get('bb_item3_icon', 'fas fa-feather-pointed'),
        ];
        return view('admin.site-content.beyond-bar.edit', compact('settings'));
    }

    public function updateBeyondBar(Request $request)
    {
        $request->validate([
            'bb_eyebrow'     => 'nullable|string|max:100',
            'bb_title'       => 'nullable|string|max:255',
            'bb_image_top'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'bb_image_left'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'bb_image_right' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            
            'bb_item1_title' => 'nullable|string|max:255',
            'bb_item1_desc'  => 'nullable|string',
            'bb_item1_icon'  => 'nullable|string|max:100',
            'bb_item2_title' => 'nullable|string|max:255',
            'bb_item2_desc'  => 'nullable|string',
            'bb_item2_icon'  => 'nullable|string|max:100',
            'bb_item3_title' => 'nullable|string|max:255',
            'bb_item3_desc'  => 'nullable|string',
            'bb_item3_icon'  => 'nullable|string|max:100',
        ]);

        SiteSetting::set('bb_eyebrow', $request->bb_eyebrow ?? 'BEYOND THE BAR');
        SiteSetting::set('bb_title',   $request->bb_title ?? 'Filmmaker, Journalist & Cultural Visionary');
        
        SiteSetting::set('bb_item1_title', $request->bb_item1_title);
        SiteSetting::set('bb_item1_desc',  $request->bb_item1_desc);
        SiteSetting::set('bb_item1_icon',  $request->bb_item1_icon);
        SiteSetting::set('bb_item2_title', $request->bb_item2_title);
        SiteSetting::set('bb_item2_desc',  $request->bb_item2_desc);
        SiteSetting::set('bb_item2_icon',  $request->bb_item2_icon);
        SiteSetting::set('bb_item3_title', $request->bb_item3_title);
        SiteSetting::set('bb_item3_desc',  $request->bb_item3_desc);
        SiteSetting::set('bb_item3_icon',  $request->bb_item3_icon);

        foreach (['bb_image_top', 'bb_image_left', 'bb_image_right'] as $field) {
            if ($request->hasFile($field)) {
                // Delete old image
                $old = SiteSetting::get($field);
                if ($old) Storage::disk('public')->delete($old);
                $path = $request->file($field)->store('beyond-bar', 'public');
                SiteSetting::set($field, $path, 'image');
            }
        }

        return redirect()->route('admin.site-content.beyond-bar')
            ->with('success', 'Beyond the Bar section updated successfully.');
    }
}
