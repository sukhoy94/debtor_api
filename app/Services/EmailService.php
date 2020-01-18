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

    public function __construct() {
        $this->mg = Mailgun::create(config('mailgun.secret'));
    }

    public function sendVerificationEmail(User $user)
    {
        if (!$user)
        {
            return;
        }

        if (App::environment('local'))
        {
            $to = config('mail.test.to');
        }
        else
        {
            $to = $user->email;
        }

        $this->mg->messages()->send(config('mailgun.domain'), [
            'from'    => $this->from['verification'],
            'to'      => $to,
            'subject' => 'Confirm your account please...',
            'text'    => 'Confirmation link: '.route('user.verify', ['token' => $user->email_verification_token]).'. If you didn\'t register in our system, please ignore this email!',
        ]);
    }

    public function sentTestEmail($text = 'Sample text', $subject = 'Test subject') {
        $this->mg->messages()->send(config('mailgun.domain'), [
            'from'    => config('mail.test.from'),
            'to'      => config('mail.test.to'),
            'subject' => $subject,
            'text'    => $text,
        ]);
    }

    public function sendErrorReportEmail($html, $subject)
    {
        $this->mg->messages()->send(config('mailgun.domain'), [
            'from'    => config('mail.test.from'),
            'to'      => 'report_error@sukhoi.hekko24.pl',
            'subject' => $subject,
            'html'    => $html,
        ]);
    }
}
