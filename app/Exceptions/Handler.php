<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
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

        // ToDo: metodo criado para erro 419 no logout (perda do @csrf) 
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('login');
        }


        // customizar redirect
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
          // return redirect('/');

           $request->session()->flash('message.level', 'erro');
           $request->session()->flash('message.content', 'Você não tem permissão para acessar essa funcionalidade.');
           $request->session()->flash('message.erro','');

           return redirect('/app');
        }

        return parent::render($request, $exception);
    }
}
