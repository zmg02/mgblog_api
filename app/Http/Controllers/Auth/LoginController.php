<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 重载登录请求
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->generateToken();

            return api_response($user->toArray());
            // return response()->json(['code'=>200, 'data'=>$user->toArray()]);
        }
        return $this->sendFailedLoginResponse($request);
    }
    /**
     * 重载登出请求
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        return api_response();
        // return response()->json(['code'=>200, 'data' => 'User logged out.'], 200);
    }

    /**
     * 重载登录表单
     *
     * @return void
     */
    public function showLoginForm()
    {
        return response()->json(['code'=>404, 'data' => '页面不存在。']);
    }
}
