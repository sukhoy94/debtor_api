<?php


namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\App;
use Mailgun\Mailgun;

class EmailService
{
    private $mg;

    private $from = [
        'verification' => 'debtor-verification@example.com',
    ];

    public function __construct()
    {
        $this->mg = Mailgun::create(env('MAILGUN_SECRET'));
    }

    public function sendVerificationEmail(User $user) {
        if (!$user) {
            return;
        }


        if (App::environment('local')) {
            $to = env('TEST_EMAIL_ADDRESS');
        }
        else {
            $to = $user->email;
        }

        $this->mg->messages()->send(env('MAILGUN_DOMAIN'), [
            'from'    => $this->from['verification'],
            'to'      => $to,
            'subject' => 'Confirm your account please...',
            'text'    => $user->email_verification_token,
        ]);
    }

    public function sentTestEmail($text = 'Sample text', $subject = 'Test subject') {
        $this->mg->messages()->send(env('MAILGUN_DOMAIN'), [
            'from'    => env('TEST_EMAIL_FROM_ADDRESS'),
            'to'      => env('TEST_EMAIL_ADDRESS'),
            'subject' => $subject,
            'text'    => $text,
        ]);
    }
}
