<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\AboutSection;
use App\Models\SiteSetting;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\Service;
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
            // The header is black, so the logo must be transparent: PNG or WebP only, and not on a solid box
            'site_logo' => ['nullable', 'file', 'mimes:png,webp', 'max:10240', function (string $attribute, $file, Closure $fail) {
                if (! $this->hasTransparentEdges($file->getRealPath())) {
                    $fail('This logo has a solid background, so it would show as a box in the header. Upload a PNG or WebP with a transparent background.');
                }
            }],
            'remove_logo' => 'nullable|boolean',
        ]);

        $oldLogo = SiteSetting::get('site_logo');

        if ($request->hasFile('site_logo')) {
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('site_logo')->store('settings', 'public');
            SiteSetting::set('site_logo', $path, 'image');
        } elseif ($request->boolean('remove_logo') && $oldLogo) {
            // Back to the bundled Josha logo
            Storage::disk('public')->delete($oldLogo);
            SiteSetting::set('site_logo', '', 'image');
        }

        return redirect()->route('admin.site-content.settings')
            ->with('success', 'Site settings updated successfully.');
    }

    // True when most of the image's corners and edge midpoints are (nearly) transparent
    private function hasTransparentEdges(string $path): bool
    {
        $image = @imagecreatefromstring((string) file_get_contents($path));
        if (! $image) {
            return true; // cannot inspect it here: let it through rather than block the upload
        }

        $w = imagesx($image) - 1;
        $h = imagesy($image) - 1;
        $points = [[0, 0], [$w, 0], [0, $h], [$w, $h], [intdiv($w, 2), 0], [intdiv($w, 2), $h], [0, intdiv($h, 2)], [$w, intdiv($h, 2)]];

        $transparent = 0;
        foreach ($points as [$x, $y]) {
            // GD alpha: 0 = opaque … 127 = fully transparent
            if (imagecolorsforindex($image, imagecolorat($image, $x, $y))['alpha'] >= 100) {
                $transparent++;
            }
        }
        imagedestroy($image);

        return $transparent >= 5;
    }

    // ==================== UPDATES (news posts on /updates, stored in the blogs table) ====================
    /** Field names used in validation messages on the Updates form. */
    private const UPDATE_FIELDS = ['image' => 'cover image', 'description' => 'content'];

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
        ], [], self::UPDATE_FIELDS);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.site-content.blogs')
            ->with('success', 'Update created successfully.');
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
        ], [], self::UPDATE_FIELDS);

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
            ->with('success', 'Update saved successfully.');
    }

    public function deleteBlog($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();

        return redirect()->route('admin.site-content.blogs')
            ->with('success', 'Update deleted successfully.');
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
}
