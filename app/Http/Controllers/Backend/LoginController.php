<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CommonFunctions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use App\Repository\Contracts\UserInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    protected $user;

    public function __construct(
        UserInterface $user
    ) {
        $this->user = $user;
        $this->middleware('guest')->except('logout');
    }
    /**
     * Index
     *
     * @param
     *
     * @return mixed
     *
     * @author SD
     */
    public function index() {
        return view('admin.login');
    }

    /**
     * Check login admin
     *
     * @param  Request $request
     *
     * @return mixed
     *
     * @author SD
     */
    public function checkLogin(Request $request) {
        $rules = [
            'email' =>'required|email',
            'password' => 'required|min:8'
        ];
        $messages = [
            'email.required' => 'Email là trường bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        $remember = ($request->has('remember')) ? true : false;
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $email = $request->input('email');
            $password = $request->input('password');
            if(Auth::guard('admin')->attempt(['email' => $email, 'password' => $password], $remember)) {
                $user = Auth::guard('admin')->user();
                if ($user->status != Config::get('constants.STATUS.ACTIVE') || $user->type == Config::get('constants.TYPE_USER.MEMBER')) {
                    Auth::guard('admin')->logout();
                    $errors = new MessageBag(['errorlogin' => 'Tài khoản chưa được kích hoạt hoặc không có quyền']);
                    return redirect()->back()->withInput()->withErrors($errors);
                }
                $scripts = '<script>$(window).load(function (){$.gritter.add({title: "Administrator", text: "Chào mừng Admin đăng nhập hệ thống", image: "/assets/images/user-profile-1.jpg", class_name: "bg-success", sticky: false})});</script>';
                return redirect()->intended('/admin/dashboard')->with('welcomeCode', $scripts);
            } else {
                $errors = new MessageBag(['errorlogin' => 'Email hoặc mật khẩu không đúng']);
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
    }

    /**
     * Logout
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function logout() {
        Auth::guard('admin')->logout();
        return redirect('/admin');
    }

    /**
     * Forgot password
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function forgot() {
        return view('admin.forgot');
    }

    /**
     * Send information gorgot password
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function sendForgot(Request $request) {
        $requestAll = $request->all();
        $validator = Validator::make($requestAll, [
            'email' => 'required|email|exists:users',
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()]);
        }
        try {
            $email = $requestAll['email'];
            $userModel = $this->user->firstByField('email', $email, ['id', 'email', 'name']);
            $hash = Str::random(100);
            DB::beginTransaction();
            $this->user->update([
                'token_password' => $hash,
                'expire_at' => Carbon::now()->toDateTimeString()
            ], $userModel->id);
            $url = url('/admin/resetPassword/') . '?email='.$email .'&token='. $hash;
            $result['name'] = $userModel->name;
            $result['link'] =  $url;
            Mail::send('emails.password_reset_email', $result, function ($message) use ($email) {
                $message->to($email)->subject('Mail xác nhận quên mật khẩu từ '.config('app.name'));
            });
            DB::commit();
            $direct_link = url('/admin');
            return response()->json(['status' => 200, 'message' => "Thư khôi phục mật khẩu đã được gửi đến $email. Vui lòng kiểm tra hộp thư" ,'direct_link' => $direct_link]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => $ex->getMessage()]);
        }
    }

    /**
     * reset password
     *
     * @param mixed
     *
     * @return mixed
     *
     * @author SD
     */
    public function resetPassword(Request $request) {
        $user = $this->user->firstWhere(['email' => $request->input('email'), 'token_password' => $request->input('token')], ['id', 'email', 'token_password', 'expire_at']);
        if ($request->isMethod('post')) {
            if ($user !== null) {
                if (!CommonFunctions::checkExpireTime($user->expire_at)) {
                    $resetPassword = $request->resetPassword;
                    $validator = Validator::make($request->all(), [
                        'resetPassword' => 'required|min:8',
                        'confirmResetPassword' => 'required_with:resetPassword|same:resetPassword'
                    ],[
                        'resetPassword.required' => 'Mật khẩu mới không được bỏ trống',
                        'resetPassword.min' => 'Mật khẩu tối thiểu 8 ký tự',
                        'confirmResetPassword.same' => 'Xác nhận mật khẩu chưa chính xác',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['status' => 0, 'message' => $validator->errors()->first()]);
                    }
                    try{
                        DB::beginTransaction();
                        $this->user->update([
                            'password' => bcrypt($resetPassword),
                            'token_password' => null,
                            'expire_at' => null,
                        ], $user->id);
                        DB::commit();
                        Session::flash('success', 'Mật khẩu được khôi phục thành công!');
                        return response()->json([
                            'status' => 200,
                            'message' => 'Mật khẩu được khôi phục thành công!',
                            'redirect_link' => route('admin')
                        ]);

                    } catch(\PDOException $ex){
                        DB::rollBack();
                        return response()->json(['status' => 0, 'message' => $ex->getMessage()]);
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => 'Đường dẫn khôi phục mật khẩu đã hết hạn. Vui lòng gửi lại yêu cầu']);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'Có gì đó sai sai :)))']);
            }
        } elseif ($request->isMethod('get') && $user !== null) {
            if (CommonFunctions::checkExpireTime($user->expire_at)){
                return redirect('admin')->with('error', 'Đường dẫn khôi phục mật khẩu đã hết hạn. Vui lòng gửi lại yêu cầu');
            }
            return view('admin.reset_password');
        } else {
            return abort(404);
        }
    }
}
