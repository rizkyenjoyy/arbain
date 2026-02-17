<?php

namespace App\Notifications;

use App\Models\Jamaah;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewJamaahAddedNotification extends Notification
{
    use Queueable;

    public $jamaah;

    public function __construct(Jamaah $jamaah)
    {
        $this->jamaah = $jamaah;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Menambahkan channel mail
    }
    // Konfigurasi isi notifikasi untuk email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Jamaah Approved: ' . $this->jamaah->nama)
            ->greeting('Assalamualaikum ' . $notifiable->name . ',')
            ->line('Kami dengan senang hati memberitahukan bahwa jamaah yang Anda ajukan telah disetujui.')
            ->line('Berikut detail jamaah:')
            ->line('Nama Jamaah: ' . $this->jamaah->nama)
            ->line('NIK: ' . $this->jamaah->nik)
            ->line('Nomor Paspor: ' . $this->jamaah->no_paspor)
            ->line('Paket: ' . ($this->jamaah->package->name ?? 'Tidak ada paket yang dipilih'))
            ->action('Lihat Detail Jamaah', url('/jamaah/' . $this->jamaah->id))
            ->line('Terima kasih telah mempercayakan layanan kami.');
    }




    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Jamaah baru ditambahkan: ' . $this->jamaah->nama,
            'jamaah_id' => $this->jamaah->id,
            'package_id' => $this->jamaah->package_id,
            'referensi_id' => $this->jamaah->referensi_id,
        ];
    }
}
