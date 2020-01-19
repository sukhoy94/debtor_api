<?php


namespace App\Services;


use App\Jobs\SendVerificationEmail;
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
        if (App::environment('local'))
        {
            SendVerificationEmail::dispatchNow($user);
        }
        else
        {
            SendVerificationEmail::dispatch($user);
        }
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
