<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Admin → Cities: the city gold rate pages (/gold-rate/{state}/{city}), the header menu cities and the drawer list
class CityController extends Controller
{
    private const SLUG_PATTERN = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    public function index()
    {
        $cities = City::ordered()->get();

        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        // New cities go to the end by default
        $nextSortOrder = (int) City::max('sort_order') + 1;

        return view('admin.cities.create', compact('nextSortOrder'));
    }

    public function store(Request $request)
    {
        City::create($this->validated($request));

        return redirect()->route('admin.cities.index')
            ->with('success', 'City added successfully.');
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $city->update($this->validated($request, $city));

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully.');
    }

    private function validated(Request $request, ?City $city = null): array
    {
        // Slugs are compared in lower case without stray spaces
        $request->merge([
            'state_slug' => strtolower(trim((string) $request->input('state_slug'))),
            'slug'       => strtolower(trim((string) $request->input('slug'))),
        ]);

        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'state'      => 'required|string|max:100',
            'state_slug' => ['required', 'string', 'max:100', 'regex:' . self::SLUG_PATTERN],
            'slug'       => [
                'required', 'string', 'max:100', 'regex:' . self::SLUG_PATTERN,
                Rule::unique('cities', 'slug')
                    ->where('state_slug', $request->input('state_slug'))
                    ->ignore($city?->id),
            ],
            'sort_order' => 'nullable|integer|min:-100000|max:100000',
        ], [
            'state_slug.regex' => 'Use lowercase letters, numbers and single hyphens only (e.g. tamil-nadu).',
            'slug.regex'       => 'Use lowercase letters, numbers and single hyphens only (e.g. navi-mumbai).',
            'slug.unique'      => 'This state already has a city with this slug.',
        ]);

        $data['sort_order']   = $data['sort_order'] ?? 0; // blank means 0 (the column is NOT NULL)
        $data['show_in_menu'] = $request->boolean('show_in_menu');
        $data['is_active']    = $request->boolean('is_active');

        return $data;
    }
}
