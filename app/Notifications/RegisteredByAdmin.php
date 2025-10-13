<?php

namespace App\Notifications;

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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $notifiable->email = $this->otherEmail ?? $notifiable->email;

        return (new MailMessage)
            ->subject("Ready to start new journey?")
            ->greeting("Hello Young Entrepreneur,")
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
