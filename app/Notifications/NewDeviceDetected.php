<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsappChannel;
use App\Notifications\Messages\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDeviceDetected extends Notification
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
            ->subject("New Device Detected Notification")
            ->greeting("Hello {$this->fullname},")
            ->line("We have detected a new device used to access your account ({$this->phone}).")
            ->line("If this wasn't you, please change your password immediately.")
            ->action("View Activity", url(route('profile.security.index')))
            ->line("If this was a legitimate activity, no further action is required.");
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): WhatsappMessage
    {
        return (new WhatsappMessage)
            ->phone($notifiable->phone)
            ->message("👋 Hello {$this->fullname}!\n\nWe noticed a new device accessed your account ({$this->phone}). If this wasn't you, please change your password immediately.\n\n🔗 View your account activity: " . url(route('profile.security.index')) . "\n\nIf this was you, no further action is needed.\n\nThank you,\nWarga Plus Team");
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
