<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewDepositNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $userName,
        public float $amount,
    ) {}

    public function via(User $user): array
    {
        return ['database'];
    }

    public function toArray(User $user): array
    {
        return [
            'title' => 'Novo Depósito',
            'message' => "{$this->userName} fez um depósito de R$ " . number_format($this->amount, 2, ',', '.'),
        ];
    }
}
