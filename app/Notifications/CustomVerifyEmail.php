<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
class CustomVerifyEmail extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

  public function toMail($notifiable)
{
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
        [
            'id' => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification()),
        ]
    );

    return (new MailMessage)
        ->subject('Confirm your email for NextLevelGaming!')
        ->view('emails.verify-email', [
            'user' => $notifiable,
            'verificationUrl' => $verificationUrl
        ]);
}
}
