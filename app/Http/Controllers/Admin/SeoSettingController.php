<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoSettingController extends Controller
{
    public function edit(): View
    {
        $settings = Setting::allKeyed();

        return view('admin.seo-settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'seo_title'                   => ['nullable', 'string', 'max:70'],
            'seo_description'             => ['nullable', 'string', 'max:160'],
            'seo_keywords'                => ['nullable', 'string', 'max:255'],
            'seo_robots'                  => ['nullable', 'in:index,follow|index,nofollow|noindex,follow|noindex,nofollow'],
            'seo_og_title'                => ['nullable', 'string', 'max:95'],
            'seo_og_description'          => ['nullable', 'string', 'max:200'],
            'seo_og_image'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'seo_google_analytics'        => ['nullable', 'string', 'max:20', 'regex:/^(G-[A-Z0-9]+|UA-\d+-\d+)?$/'],
            'seo_google_site_verification'=> ['nullable', 'string', 'max:100'],
        ]);

        // OG image upload
        if ($request->hasFile('seo_og_image')) {
            $old = Setting::get('seo_og_image');
            if ($old && file_exists(public_path('uploads/seo/' . $old))) {
                unlink(public_path('uploads/seo/' . $old));
            }
            if (! is_dir(public_path('uploads/seo'))) {
                mkdir(public_path('uploads/seo'), 0755, true);
            }
            $file = $request->file('seo_og_image');
            $name = bin2hex(random_bytes(8)) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move(public_path('uploads/seo'), $name);
            $data['seo_og_image'] = $name;
        } else {
            unset($data['seo_og_image']);
        }

        Setting::setMany($data);

        return back()->with('success', 'Paramètres SEO enregistrés avec succès.');
    }

    public function deleteOgImage(): RedirectResponse
    {
        $old = Setting::get('seo_og_image');
        if ($old && file_exists(public_path('uploads/seo/' . $old))) {
            unlink(public_path('uploads/seo/' . $old));
        }
        Setting::set('seo_og_image', null);

        return back()->with('success', 'Image OG supprimée.');
    }
}
