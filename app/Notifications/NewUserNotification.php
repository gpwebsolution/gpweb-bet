<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $userName,
        public string $userEmail,
    ) {}

    public function via(User $user): array
    {
        return ['database'];
    }

    public function toArray(User $user): array
    {
        return [
            'title' => 'Novo Cadastro',
            'message' => "{$this->userName} ({$this->userEmail}) acabou de se cadastrar.",
        ];
    }
}
