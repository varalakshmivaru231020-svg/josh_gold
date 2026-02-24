<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'year'         => 'nullable|string|max:50',
            'category'     => 'nullable|string|max:100',
            'book_icon'    => 'nullable|string|max:20',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'images.*'     => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'    => 'boolean',
        ]);

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('products/covers', 'public');
        }

        // Handle gallery images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
        }

        $validated['images']    = $images;
        $validated['is_active'] = $request->has('is_active');

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Publication / Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'year'         => 'nullable|string|max:50',
            'category'     => 'nullable|string|max:100',
            'book_icon'    => 'nullable|string|max:20',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'images.*'     => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'    => 'boolean',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('products/covers', 'public');
        }

        // Handle gallery images
        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
        }

        $validated['images']    = $images;
        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Publication / Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete cover image
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }

        // Delete gallery images
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function removeImage(Product $product, $index)
    {
        $images = $product->images;
        if (isset($images[$index])) {
            Storage::disk('public')->delete($images[$index]);
            unset($images[$index]);
            $images = array_values($images); // Reindex array
            $product->update(['images' => $images]);
        }

        return back()->with('success', 'Image removed successfully.');
    }

    public function removeCover(Product $product)
    {
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
            $product->update(['cover_image' => null]);
        }

        return back()->with('success', 'Cover image removed successfully.');
    }
}