<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Setting, Expense};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string',
            'store_email' => 'nullable|email',
            'tax_percent' => 'required|numeric|min:0|max:100',
            'receipt_footer' => 'nullable|string',
        ]);

        $keys = ['store_name', 'store_address', 'store_phone', 'store_email', 'tax_percent', 'receipt_footer', 'currency'];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->$key);
            }
        }

        if ($request->hasFile('logo')) {
            $old = Setting::get('logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $path);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
