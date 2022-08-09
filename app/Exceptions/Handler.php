<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * @throws \Throwable
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
        // 如果您使用 Laravel 服务其他页面，则必须编辑代码以使用Accept标头，否则来自常规请求的 404 错误也会返回 JSON。
        // if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['code' => 404, 'error' => 'Resource not found'], 404);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $guards = $exception->guards();

        // return $request->expectsJson()
        //     ? response()->json(['code'=>401, 'message'=>$exception->getMessage()])
        //     : redirect()->guest(
        //         in_array('admin', $guards) ? route('admin.login') : route('login')
        //     );
        return $request->expectsJson()
            ? response()->json(['code' => 401, 'message' => '未认证'])
            : response()->json(['code' => 401, 'message' => '未认证']);
    }
}
