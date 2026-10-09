<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', ['settings' => Setting::current()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:150'],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'call_number' => ['required', 'string', 'max:20'],
            'sms_number' => ['required', 'string', 'max:20'],
            'site_base_url' => ['required', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'x_url' => ['nullable', 'url', 'max:255'],
        ]);

        Setting::current()->update($data);

        return back()->with('status', 'Settings saved.');
    }
}
