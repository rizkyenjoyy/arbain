<?php

namespace App\Listeners;

use App\Events\JamaahStatusUpdated;
use App\Notifications\JamaahApprovedNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendJamaahApprovedNotification
{
    public function handle(JamaahStatusUpdated $event)
    {
        // Hanya kirim notifikasi jika status Jamaah berubah ke 'approved'
        if ($event->jamaah->status === 'approved') {
            // Dapatkan agen terkait dari jamaah
            $agen = User::find($event->jamaah->referensi_id);

            if ($agen) {
                // Kirim notifikasi ke agen
                $agen->notify(new JamaahApprovedNotification($event->jamaah));
            }
        }
    }
}
