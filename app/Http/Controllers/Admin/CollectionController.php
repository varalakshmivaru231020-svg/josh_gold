<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

// Admin → Collections: the category pages (/collections/{slug}) and the "Shop by category" tiles on the home page
class CollectionController extends Controller
{
    private const SLUG_PATTERN = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    // Uploads: JPG plus the formats that keep transparency (PNG, WebP, GIF); 5 MB each
    private const IMAGE_RULE = 'image|mimes:jpeg,jpg,png,webp,gif|max:5120';

    // Uploaded images are kept on the public disk under this folder
    private const UPLOAD_DIR = 'collections';

    private const MAX_GALLERY = 12;

    public function index()
    {
        $collections = Collection::ordered()->get();

        return view('admin.collections.index', compact('collections'));
    }

    public function create()
    {
        // New collections go to the end by default
        $nextSortOrder = (int) Collection::max('sort_order') + 1;

        return view('admin.collections.create', compact('nextSortOrder'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store(self::UPLOAD_DIR, 'public');
        }

        $gallery = [];
        foreach ($request->file('gallery_uploads', []) as $file) {
            $gallery[] = $file->store(self::UPLOAD_DIR, 'public');
        }
        $data['gallery'] = $gallery ?: null;

        Collection::create($data);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection added successfully.');
    }

    public function edit(Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $data = $this->validated($request, $collection);

        // Main image: a new upload replaces the old one; "remove" goes back to the stock photo
        if ($request->hasFile('image')) {
            $this->deleteUpload($collection->image);
            $data['image'] = $request->file('image')->store(self::UPLOAD_DIR, 'public');
        } elseif ($request->boolean('remove_image') && $collection->image) {
            $this->deleteUpload($collection->image);
            $data['image'] = null;
        }

        // Gallery: drop the ticked photos, then add the new uploads at the end
        $current = array_values(array_filter((array) ($collection->gallery ?? []), 'is_string'));
        $remove  = array_intersect($current, (array) $request->input('gallery_remove', []));
        foreach ($remove as $path) {
            $this->deleteUpload($path);
        }
        $gallery = array_values(array_diff($current, $remove));
        foreach ($request->file('gallery_uploads', []) as $file) {
            $gallery[] = $file->store(self::UPLOAD_DIR, 'public');
        }
        $data['gallery'] = $gallery ?: null;

        $collection->update($data);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection updated successfully.');
    }

    public function destroy(Collection $collection)
    {
        $this->deleteUpload($collection->image);
        foreach ((array) ($collection->gallery ?? []) as $path) {
            $this->deleteUpload(is_string($path) ? $path : null);
        }

        $collection->delete();

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection deleted successfully.');
    }

    private function validated(Request $request, ?Collection $collection = null): array
    {
        // Slugs are compared in lower case without stray spaces
        $request->merge(['slug' => strtolower(trim((string) $request->input('slug')))]);

        $metal     = (string) $request->input('metal');
        $isBullion = $request->input('type') === 'bullion';
        $current   = count((array) ($collection?->gallery ?? [])) - count((array) $request->input('gallery_remove', []));

        $data = $request->validate([
            'name'              => 'required|string|max:120',
            'slug'              => [
                'required', 'string', 'max:120', 'regex:' . self::SLUG_PATTERN,
                Rule::unique('collections', 'slug')->ignore($collection?->id),
            ],
            'type'              => ['required', Rule::in(array_keys(Collection::TYPES))],
            'metal'             => ['required', Rule::in(array_keys(Collection::METALS))],
            'form'              => [Rule::requiredIf($isBullion), 'nullable', Rule::in(array_keys(Collection::FORMS))],
            'tagline'           => 'nullable|string|max:160',
            'intro'             => 'nullable|string|max:2000',
            'body'              => 'nullable|string|max:20000',
            'weights'           => [
                'nullable', 'string', 'max:255',
                function ($attribute, $value, $fail) {
                    if ($bad = Collection::invalidWeights($value)) {
                        $fail('These weights could not be read: ' . implode(', ', $bad) . '. Use grams separated by commas, e.g. 1, 2, 5, 116.64 (10 tola).');
                    }
                },
            ],
            'purities'          => [
                'nullable', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($metal) {
                    $allowed = Collection::purityOptions($metal);
                    $bad = [];
                    foreach (explode(',', (string) $value) as $item) {
                        $item = strtoupper(str_replace(' ', '', $item));
                        if ($item !== '' && ! in_array($item, $allowed, true)) {
                            $bad[] = $item;
                        }
                    }
                    if ($bad) {
                        $fail('Unknown ' . ($metal ?: 'metal') . ' purity: ' . implode(', ', $bad) . '. Use any of: ' . implode(', ', $allowed) . '.');
                    }
                },
            ],
            'image'             => 'nullable|' . self::IMAGE_RULE,
            'gallery_uploads'   => 'nullable|array|max:' . max(0, self::MAX_GALLERY - max(0, $current)),
            'gallery_uploads.*' => self::IMAGE_RULE,
            'gallery_remove'    => 'nullable|array',
            'gallery_remove.*'  => 'string',
            'sort_order'        => 'nullable|integer|min:-100000|max:100000',
        ], [
            'slug.regex'            => 'Use lowercase letters, numbers and single hyphens only (e.g. gold-coins).',
            'slug.unique'           => 'Another collection already uses this slug.',
            'form.required'         => 'Choose coin, bar or biscuit for a bullion collection.',
            'gallery_uploads.max'   => 'A gallery can hold up to ' . self::MAX_GALLERY . ' photos.',
            'gallery_uploads.*.image' => 'Gallery files must be images (JPG, PNG, WebP or GIF).',
            'gallery_uploads.*.mimes' => 'Gallery files must be JPG, PNG, WebP or GIF.',
            'gallery_uploads.*.max'   => 'Each gallery photo can be up to 5 MB.',
        ]);

        // Files and remove lists are handled by the caller
        unset($data['image'], $data['gallery_uploads'], $data['gallery_remove']);

        // Stored in a tidy form: "1, 2, 116.64 (10 tola)" and "24K,22K"
        $weights = Collection::parseWeights($data['weights'] ?? null);
        $data['weights'] = $isBullion && $weights
            ? implode(', ', array_map(fn ($w) => rtrim(rtrim(number_format($w['grams'], 3, '.', ''), '0'), '.') . ($w['name'] ? " ({$w['name']})" : ''), $weights))
            : null;
        $data['purities'] = implode(',', Collection::parsePurities($data['purities'] ?? null, $metal)) ?: null;
        $data['form']     = $isBullion ? $data['form'] : null;

        $data['sort_order']   = $data['sort_order'] ?? 0; // blank means 0 (the column is NOT NULL)
        $data['show_on_home'] = $request->boolean('show_on_home');
        $data['is_active']    = $request->boolean('is_active');

        return $data;
    }

    // Deletes an uploaded image from the public disk. Images shipped with the site (images/…) are never deleted.
    private function deleteUpload(?string $path): void
    {
        if (! $path || Collection::isPublicAsset($path) || preg_match('#^https?://#i', $path)) {
            return;
        }

        // Keep it if another collection still uses the same file
        $inUse = Collection::where('image', $path)->count()
            + Collection::whereNotNull('gallery')->get(['gallery'])->filter(fn ($c) => in_array($path, (array) $c->gallery, true))->count();
        if ($inUse > 1) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
