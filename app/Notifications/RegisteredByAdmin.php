<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsappChannel;
use App\Notifications\Messages\WhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisteredByAdmin extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $username,
        protected string $email,
        protected string $generatedPassword,
        protected ?string $otherEmail = null
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
        $notifiable->email = $this->otherEmail ?? $notifiable->email;

        return (new MailMessage)
            ->subject("Ready to start new journey?")
            ->greeting("Hello People,")
            ->line("Congratulations! Your account has been created by the admin.")
            ->line("Below are your login credentials:")
            ->line("• Username: {$this->username}")
            ->line("• Email: {$this->email}")
            ->line("• Temporary Password: {$this->generatedPassword}")
            ->line("For your security, please change your password after logging in for the first time.")
            ->action('Login Now', url(route('login')))
            ->line("Welcome aboard! We wish you success on your journey.");
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): WhatsappMessage
    {
        return (new WhatsappMessage)
            ->phone($notifiable->phone)
            ->message("👋 Hello People!\n\nCongratulations! Your account has been created by the admin.\n\nHere are your login credentials:\n• Username: {$this->username}\n• Email: {$this->email}\n• Temporary Password: {$this->generatedPassword}\n\nPlease change your password after logging in for the first time for security reasons.\n\n🔗 Login now: " . url(route('login')) . "\n\nWelcome aboard! We wish you success on your journey.\n\nThank you,\nWarga Plus Team");
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
