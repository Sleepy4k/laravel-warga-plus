<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsappChannel;
use App\Notifications\Messages\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $fullname,
        protected string $phone
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsappChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Account password has been changed')
            ->greeting("Hello {$this->fullname},")
            ->line("The password for your account ({$this->phone}) has been changed successfully.")
            ->line('If you did not make this change or if you believe an unauthorized person has accessed your account, please contact support immediately.')
            ->line('If you have any questions or concerns, feel free to reach out to our support team.');
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): WhatsappMessage
    {
        return (new WhatsappMessage)
            ->phone($notifiable->phone)
            ->message("👋 Hello {$this->fullname}!\n\nWe wanted to inform you that the password for your account ({$this->phone}) has been changed successfully. If you did not make this change or suspect any unauthorized access, please contact our support team immediately.\n\nThank you,\nWarga Plus Team");
    }

    /**
     * Get the array representation of the notification.
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
