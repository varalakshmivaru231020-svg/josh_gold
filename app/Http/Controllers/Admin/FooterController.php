<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// Admin → Footer: the footer's link columns, their headings, the about text and the bottom note
class FooterController extends Controller
{
    // A footer link may be a site path (/gold-rate-today), a full http(s) URL, tel: or mailto:
    private const URL_PATTERN = '#^(/\S*|https?://\S+|tel:\S+|mailto:\S+)$#i';

    public function index()
    {
        $links = FooterLink::ordered()->get()->groupBy('column_key');

        $columns = [];
        foreach (FooterLink::COLUMNS as $key => $default) {
            $columns[$key] = [
                'default' => $default,
                'heading' => FooterLink::heading($key),
                'links'   => $links->get($key, collect()),
            ];
        }

        $about = FooterLink::about();
        $note  = FooterLink::note();

        return view('admin.footer.index', compact('columns', 'about', 'note'));
    }

    // Column headings, about text and note (site_settings)
    public function updateSettings(Request $request)
    {
        $rules = [
            'footer_about' => 'nullable|string|max:1000',
            'footer_note'  => 'nullable|string|max:2000',
        ];
        foreach (array_keys(FooterLink::COLUMNS) as $key) {
            $rules['footer_heading_' . $key] = 'required|string|max:60';
        }
        $data = $request->validate($rules, [
            'required' => 'Please enter a column heading.',
        ]);

        foreach (array_keys(FooterLink::COLUMNS) as $key) {
            SiteSetting::set('footer_heading_' . $key, trim($data['footer_heading_' . $key]));
        }
        // An empty about text or note is saved as empty, which hides it on the website
        SiteSetting::set('footer_about', trim((string) ($data['footer_about'] ?? '')), 'textarea');
        SiteSetting::set('footer_note', trim((string) ($data['footer_note'] ?? '')), 'textarea');

        return redirect()->route('admin.footer.index')
            ->with('success', 'Footer text updated successfully.');
    }

    // Sort orders of many links at once: order[link id] => sort order
    public function updateOrder(Request $request)
    {
        $data = $request->validate([
            'order'   => 'required|array',
            'order.*' => 'nullable|integer|min:-100000|max:100000',
        ]);

        foreach (FooterLink::whereIn('id', array_keys($data['order']))->get() as $link) {
            $link->update(['sort_order' => (int) ($data['order'][$link->id] ?? 0)]);
        }

        return redirect()->route('admin.footer.index')
            ->with('success', 'Link order saved.');
    }

    public function create(Request $request)
    {
        $column = array_key_exists($request->query('column'), FooterLink::COLUMNS)
            ? $request->query('column')
            : array_key_first(FooterLink::COLUMNS);

        // New links go to the end of their column by default
        $nextSortOrder = (int) FooterLink::where('column_key', $column)->max('sort_order') + 1;

        return view('admin.footer.create', [
            'column'        => $column,
            'nextSortOrder' => $nextSortOrder,
            'columns'       => $this->columnOptions(),
        ]);
    }

    public function store(Request $request)
    {
        FooterLink::create($this->validated($request));

        return redirect()->route('admin.footer.index')
            ->with('success', 'Footer link added successfully.');
    }

    public function edit(FooterLink $link)
    {
        return view('admin.footer.edit', [
            'link'    => $link,
            'columns' => $this->columnOptions(),
        ]);
    }

    public function update(Request $request, FooterLink $link)
    {
        $link->update($this->validated($request));

        return redirect()->route('admin.footer.index')
            ->with('success', 'Footer link updated successfully.');
    }

    public function destroy(FooterLink $link)
    {
        $link->delete();

        return redirect()->route('admin.footer.index')
            ->with('success', 'Footer link deleted successfully.');
    }

    // column_key => current heading, for the column picker
    private function columnOptions(): array
    {
        $options = [];
        foreach (array_keys(FooterLink::COLUMNS) as $key) {
            $options[$key] = FooterLink::heading($key);
        }

        return $options;
    }

    private function validated(Request $request): array
    {
        $request->merge(['url' => $this->relativeIfOwnSite(trim((string) $request->input('url')))]);

        $data = $request->validate([
            'column_key' => ['required', Rule::in(array_keys(FooterLink::COLUMNS))],
            'label'      => 'required|string|max:255',
            'url'        => ['required', 'string', 'max:500', 'regex:' . self::URL_PATTERN],
            'sort_order' => 'nullable|integer|min:-100000|max:100000',
        ], [
            'url.regex' => 'Enter a page path starting with / (e.g. /gold-rate-today) or a full URL starting with https://.',
        ]);

        $data['sort_order']      = $data['sort_order'] ?? 0; // blank means 0 (the column is NOT NULL)
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $data['is_active']       = $request->boolean('is_active');

        return $data;
    }

    // A full URL to a page of this website is stored as its path, so the link works on any domain
    private function relativeIfOwnSite(string $url): string
    {
        foreach (array_filter([request()->getSchemeAndHttpHost(), rtrim((string) config('app.url'), '/')]) as $root) {
            if (Str::startsWith(strtolower($url), strtolower($root . '/')) || strtolower($url) === strtolower($root)) {
                return '/' . ltrim(substr($url, strlen($root)), '/');
            }
        }

        return $url;
    }
}
