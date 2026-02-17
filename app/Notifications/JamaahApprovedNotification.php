<?php

namespace App\Notifications;

use App\Models\Jamaah;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class JamaahApprovedNotification extends Notification
{
    use Queueable;

    protected $jamaah;

    public function __construct(Jamaah $jamaah)
    {
        $this->jamaah = $jamaah;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Jamaah ' . $this->jamaah->nama . ' telah disetujui.',
            'jamaah_id' => $this->jamaah->id,
            'jamaah_nama' => $this->jamaah->nama,
            'jamaah_nik' => $this->jamaah->nik,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Jamaah ' . $this->jamaah->nama . ' telah disetujui.',
            'jamaah_id' => $this->jamaah->id,
            'jamaah_nama' => $this->jamaah->nama,
            'jamaah_nik' => $this->jamaah->nik,
        ]);
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Jamaah Approved')
            ->line('Jamaah ' . $this->jamaah->nama . ' telah disetujui.')
            ->action('Lihat Detail', url('/jamaah/' . $this->jamaah->id));
    }

}

