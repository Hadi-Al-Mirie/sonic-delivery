<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::orderBy('created_at', 'desc')->paginate(10);

        return view('dashboard.stores.index', compact('stores'));
    }

    public function show($id)
    {
        $store = Store::with('products')->findOrFail($id);
        return view('dashboard.stores.show', compact('store'));
    }


    public function create()
    {
        return view('dashboard.stores.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store_logos', 'public');
        }

        Store::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'logo' => $logoPath ?? null,
        ]);

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        return view('dashboard.stores.edit', compact('store'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $store = Store::findOrFail($id);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store_logos', 'public');
        }

        $store->update([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'logo' => isset($logoPath) ? $logoPath : $store->logo,
        ]);

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }
}
