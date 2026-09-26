<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\CtaContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CtaController extends Controller
{
    public function edit()
    {
        return view('admin.site-content.cta.edit', [
            'cta'      => CtaContent::get(),
            'defaults' => CtaContent::DEFAULTS,
            'whatsapp' => SiteSetting::get('contact_whatsapp'),
        ]);
    }

    public function update(Request $request)
    {
        // a page path, a full link, or #anchor / tel: / mailto: — never javascript:
        $link = ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/|#|tel:|mailto:)/i'];

        $request->validate([
            'cta_image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'cta_heading'       => 'nullable|string|max:120',
            'cta_subtext'       => 'nullable|string|max:300',
            'cta_button1_label' => 'nullable|string|max:40',
            'cta_button1_url'   => $link,
            'cta_button2_label' => 'nullable|string|max:40',
            'cta_button2_url'   => $link,
        ], [
            'cta_button1_url.regex' => 'Use a page path like /live-rates, or a full link starting with https://',
            'cta_button2_url.regex' => 'Use a page path like /contact-us, or a full link starting with https://',
        ]);

        SiteSetting::set('cta_enabled', $request->boolean('cta_enabled') ? '1' : '0');

        // Saved even when blank, so a cleared field stays cleared instead of reverting to the default
        foreach (['cta_heading', 'cta_subtext', 'cta_button1_label', 'cta_button1_url', 'cta_button2_label', 'cta_button2_url'] as $key) {
            SiteSetting::set($key, trim((string) $request->input($key, '')));
        }

        $old = SiteSetting::get('cta_image');

        if ($request->hasFile('cta_image')) {
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            SiteSetting::set('cta_image', $request->file('cta_image')->store('cta', 'public'), 'image');
        } elseif ($request->boolean('remove_image') && $old) {
            Storage::disk('public')->delete($old);
            SiteSetting::set('cta_image', '', 'image');
        }

        return redirect()->route('admin.site-content.cta')
            ->with('success', 'Call to action updated. It appears above the footer on every public page.');
    }
}
