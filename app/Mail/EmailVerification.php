<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
   public function build()
    {
        $verificationUrl = $this->getVerificationUrl();

        return $this->subject('Email Verification')
            ->view('emails.verify')
            ->with([
                'user' => $this->user,
                'verificationUrl' => $verificationUrl,
            ]);
    }

   protected function getVerificationUrl()
    {
        $temporarySignedUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->user->getKey(),
                'hash' => $this->user->email_verified_token,
            ],
            false // Set the $absolute parameter to false to exclude query parameters
        );

        return new HtmlString($temporarySignedUrl);
    }




}





