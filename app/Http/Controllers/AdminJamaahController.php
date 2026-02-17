<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jamaah;
use Illuminate\Http\Request;
use App\Notifications\NewJamaahAddedNotification;
use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\JamaahApprovedNotification;
use Illuminate\Support\Facades\Auth;

class AdminJamaahController extends Controller
{
    public function index(Request $request)
    {
        // Tandai semua notifikasi sebagai sudah dibaca

        $query = Jamaah::where('status', 'pending');

        // Search by name or NIK
        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
        }

        // Sort by specified column
        if ($request->has('sort') && in_array($request->sort, ['nama', 'nik', 'tanggal_lahir', 'status'])) {
            $query->orderBy($request->sort, $request->get('direction', 'asc'));
        }
        // Mark all JamaahAddedNotifications as read
        $jamaahNotifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\NewJamaahAddedNotification');
        foreach ($jamaahNotifications as $notification) {
            $notification->markAsRead();
        }

        // Paginate the results
        $jamaahs = $query->paginate(10); // Display 10 results per page

        return view('dashboard.jamaah.admin.index', compact('jamaahs'));
    }



    public function approve($id)
    {
        $jamaah = Jamaah::findOrFail($id);

        if ($jamaah->status === 'pending') {
            $jamaah->status = 'approved';
            $jamaah->processed_by = Auth::id(); // Set processed_by to the authenticated user
            $jamaah->save();
            // Kirim notifikasi ke agen yang terkait
            $agen = $jamaah->referensi; // Ambil agen yang terkait dengan jamaah ini
            if ($agen) {
                $agen->notify(new JamaahApprovedNotification($jamaah));
            }

            return redirect()->route('jamaah.admin.index')->with('success', 'Jamaah berhasil di-approve.');
        }

        return redirect()->route('dashboard.jamaah.admin.index')->with('error', 'Jamaah sudah diproses.');
    }

    public function reject(Jamaah $jamaah)
    {
        // Menghapus jamaah yang ditolak
        $jamaah->delete();

        return redirect()->route('jamaah.admin.index')->with('success', 'Jamaah berhasil ditolak dan dihapus.');
    }

}
