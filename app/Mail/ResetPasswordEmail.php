<?php

namespace App\Mail;

use App\Models\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    const FROM = 'debtor-administration@example.com';
    const NAME = 'Debtor Reset Password Link';

    private $resetPassword;

    /**
     * Create a new message instance.
     *
     * @param ResetPassword $resetPassword
     */
    public function __construct(ResetPassword $resetPassword)
    {
        $this->resetPassword = $resetPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(self::FROM, self::NAME)
            ->subject('Confirm registration please!')
            ->view('mails.users.reset-password')
            ->with([
                'confirmation_link' => config('app.web_base_url').'/auth/verify?t='.$this->user->getEmailVerificationToken(),
                'app_name' => config('app.name'),
            ]);
    }
}
