<?php

namespace App\Exceptions;

use App\Http\Resources\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            /*
            |-------------------------------------------------------------------
            | Retorna os erros de validação que são recebidos
            |-------------------------------------------------------------------
            | Todos os campos validados por uma classe Validator que acionam um
            | tratamento de exceção da classe ValidationException são retornados
            | na lista de errors e os valores enviados são retornados no array
            | nomeado old
            | 
            */
            if ($exception instanceof ValidationException) {
                $errors = [
                    'list' => $exception->errors(),
                    'old' => $request->all(),
                ];
                return ApiResponse::return(
                    [],
                    $errors,
                    [],
                    $exception->status
                );
            }
            /*
            |-------------------------------------------------------------------
            | Quando um modelo não é encontrado retornamos 404
            |-------------------------------------------------------------------
            */
            if ($exception instanceof ModelNotFoundException) {
                return ApiResponse::return(
                    [],
                    [],
                    [],
                    $exception->status
                );
            }
        }

        return parent::render($request, $exception);
    }
}
