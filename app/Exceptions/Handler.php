<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\BusinessException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        BusinessException::class
    ];

    /**
     * 可阅读异常类型列表，指明哪些异常类型需要转换为可阅读的方式渲染，还是不需转换直接渲染
     *
     * @var array
     */
    protected $readable = [
        ValidationException::class,
        BusinessException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response|string
     */
    public function render($request, Exception $e)
    {
        if($this->isReadable($e)){
            return responseError($e);
        }
        return parent::render($request, $e);
    }

    /**
     * 判断异常类型是否可读
     * @param Exception $e
     * @return bool
     */
    private function isReadable(Exception $e){
        foreach($this->readable as $readableEx){
            if($e instanceof $readableEx){
                return true;
            }
        }
        return false;
    }
}
