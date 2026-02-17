<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pembayaran Disetujui: ' . $this->payment->jamaah->nama)
            ->greeting('Assalamualaikum ' . $notifiable->name)
            ->line('Pembayaran sebesar Rp ' . number_format($this->payment->amount, 0, ',', '.') . ' untuk jamaah ' . $this->payment->jamaah->nama . ' telah disetujui.')
            ->line('NIK Jamaah: ' . $this->payment->jamaah->nik)
            ->line('Jumlah pembayaran akan ditambahkan ke total amount.')
            ->action('Lihat Detail Pembayaran', url('/payments/' . $this->payment->id))
            ->line('Terima kasih atas kerja sama Anda.');
    }

    public function toArray($notifiable)
    {
        return [
            'jamaah_nama' => $this->payment->jamaah->nama,
            'jamaah_nik' => $this->payment->jamaah->nik,
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
        ];
    }
}
