<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseRecordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private object $userData;

    /**
     * Create a new notification instance.
     */
    public function __construct(object $userData)
    {
        $this->userData = $userData;
    }

    /**
     * Define o tipo de notificação que será utilizada
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Faz o envio do e-mail
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject("Despesa cadastrada")
                    ->greeting("Olá, ".$this->userData->name)
                    ->line("Você cadastrou uma nova despesa");
    }

    /**
     * Formata em forma de array, os dados que poderão ser passados ao corpo do e-mail
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
