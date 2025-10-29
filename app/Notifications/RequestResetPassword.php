<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsappChannel;
use App\Notifications\Messages\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $fullname,
        protected string $url
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
            ->subject("Password Reset Request")
            ->greeting("Hello {$this->fullname},")
            ->line("You are receiving this email because we received a password reset request for your account.")
            ->action("Reset Password", $this->url)
            ->line("This link will expire in 60 minutes.")
            ->line("If you did not request a password reset, no further action is required.");
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): WhatsappMessage
    {
        return (new WhatsappMessage)
            ->phone($notifiable->phone)
            ->message("👋 Hello {$this->fullname}!\n\nWe received a request to reset your account password. You can reset your password by clicking the link below:\n\n🔗 Reset Password: {$this->url}\n\nThis link will expire in 60 minutes. If you did not request a password reset, please ignore this message.\n\nThank you,\nWarga Plus Team");
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
