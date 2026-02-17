<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jamaah;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data pengguna yang sedang login
        $user = auth()->user();

        // Ambil jamaah yang terkait dengan pengguna
        $jamaahs = Jamaah::where('referensi_id', $user->id)->get();

        return view('dashboard.profile.index', compact('user', 'jamaahs'));
    }
}
