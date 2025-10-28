<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsappChannel;
use App\Notifications\Messages\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerification extends Notification
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
            ->subject("Verify Your Email Address")
            ->greeting("Hello {$this->fullname},")
            ->line("Please click the button below to verify your email address.")
            ->action("Verify Email", $this->url)
            ->line("If you did not create an account, no further action is required.");
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): WhatsappMessage
    {
        return (new WhatsappMessage)
            ->phone($notifiable->phone)
            ->message("👋 Hello {$this->fullname}!\n\nThank you for joining Warga Plus. Please verify your phone to activate your account.\n\n🔗 Verify now: {$this->url}\n\nIf you didn't request this, please ignore this message.\n\nThank you,\nWarga Plus Team");
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
