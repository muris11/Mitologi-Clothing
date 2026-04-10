<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;

class ResetPasswordNotification extends ResetPasswordBase
{
    use Queueable;

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject('Reset Password Anda | Mitologi Clothing')
            ->view('emails.reset_password', [
                'url' => url(config('app.frontend_url') . '/shop/reset-password?token=' . $this->token . '&email=' . $notifiable->getEmailForPasswordReset()),
                'name' => $notifiable->name,
            ]);
    }
}
