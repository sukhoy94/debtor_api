<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    const FROM = 'debtor-verification@example.com';
    const NAME = 'Debtor Verification System';

    public $user;


    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
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
        return $this->from(self::FROM, self::NAME)
            ->subject('Confirm registration please!')
            ->view('mails.users.verification')
            ->with([
                'confirmation_link' => config('app.web_base_url').'/auth/verify?t='.$this->user->getEmailVerificationToken(),
                'app_name' => config('app.name'),
            ]);
    }
}
