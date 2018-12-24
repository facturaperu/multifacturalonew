<?php

namespace App\Exceptions;

use Exception;
use Http\Client\Exception\HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        if ($exception instanceof AuthenticationException) {
            if ($this->isFrontend($request)) {
                return redirect()->guest('login');
            }
            return $this->errorResponse('No se encuentra autenticado', 401, $exception);
        }
        if($exception instanceof AuthorizationException) {
            return $this->errorResponse('No posee permisos para ejecutar esta acción', 403, $exception);
        }
        if($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('No se encontró la URL especificada', 404, $exception);
        }
        if($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('El método especificado en la petición no es válido', 405, $exception);
        }
        if($exception instanceof HttpException) {
            return $this->errorResponse('', '', $exception);
        }

        if(env('APP_ENV') === 'local') {
            return $this->errorResponse('', 500, $exception);
        }

        return parent::render($request, $exception);
    }

    private function isFrontend(Request $request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

    private function errorResponse($message, $code , Exception $exception)
    {
        $message = ($message === '')?$exception->getMessage():$message;
        $code = ($code === '')?$exception->getCode():$code;
        $file = $exception->getFile();
        $line = $exception->getLine();

        return response()->json([
            'success' => false,
            'message' => $message,
            'file' => $file,
            'line' => $line
        ], $code);
    }
}