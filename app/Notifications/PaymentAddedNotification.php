<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentAddedNotification extends Notification
{
    use Queueable;

    public $payment;

    /**
     * Create a new notification instance.
     *
     * @param Payment $payment
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Menggunakan channel database dan email
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pembayaran Baru untuk Jamaah ' . $this->payment->jamaah->nama)
            ->greeting('Halo Admin,')
            ->line('Pembayaran baru telah diajukan untuk Jamaah: ' . $this->payment->jamaah->nama)
            ->line('Jumlah: Rp' . number_format($this->payment->amount, 0, ',', '.'))
            ->line('Metode Pembayaran: ' . ucfirst($this->payment->payment_method))
            ->action('Lihat Pembayaran', url('/dashboard/payments/admin')) // Sesuaikan URL halaman admin
            ->line('Silakan review dan verifikasi pembayaran tersebut.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Pembayaran baru diajukan untuk Jamaah: ' . $this->payment->jamaah->nama,
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'jamaah_name' => $this->payment->jamaah->nama,
        ];
    }
}
