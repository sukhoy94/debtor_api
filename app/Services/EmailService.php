<?php


namespace App\Services;


use App\Jobs\SendVerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Mailgun\Mailgun;
use Symfony\Component\HttpFoundation\Response;

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
        if (App::environment('local'))
        {
            $this->mg->messages()->send(config('mailgun.domain'), [
                'from'    => config('mail.test.from'),
                'to'      => config('mail.test.to'),
                'subject' => $subject,
                'text'    => $text,
            ]);
        }
        else
        {
            throw new \BadFunctionCallException('Send test email on production', Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    public function sendErrorReportEmail($html, $subject)
    {
        $this->mg->messages()->send(config('mailgun.domain'), [
            'from'    => config('mail.test.from'),
            'to'      => config('mail.errors.to'),
            'subject' => $subject,
            'html'    => $html,
        ]);
    }
}
