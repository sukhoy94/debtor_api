<?php

namespace App\Exceptions;

use App\Mail\ExceptionOccured;
use App\Services\EmailService;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception))
        {
            $this->sendEmail($exception); // sends an email
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    public function sendEmail(Throwable $exception)
    {
        $emailService = new EmailService();

        try {
            $fe = FlattenException::createFromThrowable($exception);
            $handler = new HtmlErrorRenderer(true);
            $content = $handler->getBody($fe);
            $subject = 'Error ' . $fe->getStatusCode() . ' ' . $exception->getMessage();   
            $emailService->sendErrorReportEmail($content, $subject);
        } catch (Exception $ex) {
        }
    }
}
