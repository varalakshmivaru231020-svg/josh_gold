<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    public function store(Request $request, $pageId)
    {
        $request->validate([
            'section_type' => 'required|string',
            'heading' => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:255',
        ]);

        $page = Page::findOrFail($pageId);

        $cards = $request->cards;
        if (is_string($cards)) {
            $cards = json_decode($cards, true);
        }

        $page->sections()->create([
            'section_type' => $request->section_type,
            'heading' => $request->heading,
            'subheading' => $request->subheading,
            'content' => $request->content,
            'cards' => $cards,
            'sort_order' => $page->sections()->count() + 1,
        ]);

        return redirect()->back()->with('success', 'Section added successfully.');
    }

    public function edit($id)
    {
        $section = PageSection::findOrFail($id);
        return view('admin.pages.sections.edit', compact('section'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'heading' => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $section = PageSection::findOrFail($id);
        $cards = $request->cards;

        // Handle Professional Profile Items explicitly
        if ($section->section_type === 'professional_profiles' && $request->has('profile_items')) {
            $cards = [];
            foreach ($request->profile_items as $index => $item) {
                if (!empty($item['type']) || !empty($item['description'])) {
                    $profileData = [
                        'type' => $item['type'] ?? '',
                        'description' => $item['description'] ?? '',
                        'icon' => $item['icon'] ?? 'fas fa-check',
                    ];

                    // Keep existing image if no new one uploaded
                    $oldImage = $section->cards[$index]['image'] ?? null;
                    $profileData['image'] = $oldImage;

                    if ($request->hasFile("profile_images.{$index}")) {
                        $profileData['image'] = $request->file("profile_images.{$index}")->store('sections/profiles', 'public');
                    }

                    $cards[] = $profileData;
                }
            }
        }

        if (is_string($cards)) {
            $decoded = json_decode($cards, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $cards = $decoded;
            }
        }

        // Handle Image Upload for other sections
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sections', 'public');
            if ($section->section_type === 'about_intro') {
                if (!is_array($cards)) $cards = [[]];
                if (!isset($cards[0])) $cards[0] = [];
                $cards[0]['image'] = $path;
            }
        }

        $section->update([
            'heading' => $request->heading,
            'subheading' => $request->subheading,
            'content' => $request->content,
            'cards' => $cards,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? $section->sort_order,
        ]);

        return redirect()->back()->with('success', 'Section updated successfully.');
    }

    public function destroy($id)
    {
        $section = PageSection::findOrFail($id);
        $section->delete();

        return redirect()->back()->with('success', 'Section deleted successfully.');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->order; // Array of IDs in new order
        foreach ($order as $index => $id) {
            PageSection::where('id', $id)->update(['sort_order' => $index + 1]);
        }
        return response()->json(['success' => true]);
    }
}
