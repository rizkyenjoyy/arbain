<?php

namespace App\Notifications;

use App\Models\Commission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class CommissionAddedNotification extends Notification
{
    use Queueable;

    protected $commission;

    public function __construct(Commission $commission)
    {
        $this->commission = $commission;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Komisi Baru Diterima')
            ->greeting('Assalamualaikum ' . $notifiable->name)
            ->line('Anda telah menerima komisi baru sebesar Rp ' . number_format($this->commission->amount, 0, ',', '.'))
            ->line('Terima kasih atas kontribusi Anda.')
            ->action('Lihat Detail Komisi', url('/commissions/' . $this->commission->id))
            ->line('Semoga semakin sukses dalam bekerja.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'commission_id' => $this->commission->id,
            'amount' => $this->commission->amount,
            'message' => 'Anda telah menerima komisi baru sebesar Rp ' . number_format($this->commission->amount, 0, ',', '.'),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'commission_id' => $this->commission->id,
            'amount' => $this->commission->amount,
            'message' => 'Anda telah menerima komisi baru sebesar Rp ' . number_format($this->commission->amount, 0, ',', '.'),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'commission_id' => $this->commission->id,
            'amount' => $this->commission->amount,
        ];
    }
}
