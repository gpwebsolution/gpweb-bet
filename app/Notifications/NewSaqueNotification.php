<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSaqueNotification extends Notification
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
            'title' => 'Novo Saque',
            'message' => "{$this->userName} solicitou um saque de R$ ".number_format($this->amount, 2, ',', '.'),
        ];
    }
}
