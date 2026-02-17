<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::withCount('jamaahs'); // Menambahkan count jamaah terkait dengan paket

        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort by specified column
        if ($request->has('sort') && in_array($request->sort, ['name', 'departure_date', 'price'])) {
            $query->orderBy($request->sort, $request->get('direction', 'asc'));
        }

        // Paginate the results
        $packages = $query->paginate(10);  // Display 10 results per page

        return view('dashboard.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('dashboard.packages.create');
    }

    public function store(Request $request)
    {
        // Validasi input harga sebagai numerik (tanpa pemisah ribuan)
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'departure_date' => 'required|date',
            'price' => 'required|numeric|min:0', // Harga harus numerik
        ]);

        // Menghapus pemisah ribuan dari input harga
        $requestData = $request->all();
        $requestData['price'] = str_replace('.', '', $requestData['price']);

        Package::create($requestData);

        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(Package $package)
    {
        return view('dashboard.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'departure_date' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        $requestData = $request->all();
        $requestData['price'] = str_replace('.', '', $requestData['price']);

        $package->update($requestData);

        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('packages.index')->with('success', 'Package deleted successfully.');
    }
}
