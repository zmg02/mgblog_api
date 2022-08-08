<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Model\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * 重载注册方法
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        //这里对请求进行验证。验证器方法位于RegisterController内部，
        //并确保名称、电子邮件密码和password_confirmation字段是必需的。
        $this->validator($request->all())->validate();

        //创建一个Registered事件，并将触发任何相关的观察者，
        //例如发送确认电子邮件或任何需要在创建用户后立即运行的代码。
        event(new Registered($user = $this->create($request->all())));

        //创建用户后，他就登录了
        $this->guard()->login($user);

        /**最后这就是我们想要的钩子。
         * 如果没有registered()方法或返回null，则将其重定向到其他URL。
         * 在本例中，我们只需要实现该方法来返回正确的响应。 */
        return $this->registered($request, $user)
                    ?: redirect($this->redirectPath());
    }
    /**
     * 获取用户token并返回用户数据
     *
     * @param Request $request
     * @param [type] $user
     * @return void
     */
    protected function registered(Request $request, $user)
    {
        $user->generateToken();

        return response()->json(['data' => $user->toArray()], 201);
    }
    /**
     * 重载注册表单
     *
     * @return void
     */
    public function showRegistrationForm()
    {
        return response()->json(['code'=>404, 'data' => '页面不存在。']);
    }
}
