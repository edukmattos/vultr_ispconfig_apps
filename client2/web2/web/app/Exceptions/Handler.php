<?php

namespace SisCad\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Log;
use SisCad\Mail\ExceptionOccuredMail;
use Mail;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->sendEmailException($exception); // send email exception
        }
        parent::report($exception);
    }

    public function sendEmailException(Exception $exception)
    {
        try {
            // Converte message to HTML
            $e = FlattenException::create($exception);
            $handler = new SymfonyExceptionHandler();
            $html = $handler->getHtml($e);
    
            // Send Mail Exception
            Mail::to('edukmattos@gmail.com')->send(new ExceptionOccuredMail($html));
        } catch (Exception $ex) {
            //Log.error($ex);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}