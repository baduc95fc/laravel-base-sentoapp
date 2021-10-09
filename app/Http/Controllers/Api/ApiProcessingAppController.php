<?php

namespace App\Http\Controllers\Api;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use \Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\CommonFunctions;
use Illuminate\Support\Facades\Validator;
use App\Repository\Contracts\UserInterface;
use App\Repository\Contracts\AppConfigInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserResource;

class ApiProcessingAppController extends Controller
{
    protected
        $appConfig,
        $user;

    public function __construct(
        UserInterface $user,
        AppConfigInterface $appConfig
    )
    {
        $this->appConfig = $appConfig;
        $this->user = $user;
    }

    /**
     * Đăng nhập bằng email
     *
     * @param Request $request
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function login(Request $request) {
        $apiFormat['status'] = Config::get('constants.STATUS.INACTIVE');
        $validator = Validator::make($request->only([
            'email',
            'password'
        ]), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [

        ]);
        if ($validator->fails()) {
            $apiFormat['message'] = $validator->errors()->first();
            return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }
        try {
            DB::beginTransaction();
            $email = $request->input('email');
            $password = $request->input('password');
            if (!$token = JWTAuth::attempt(['email' => $email, 'password' => $password])) {
                $apiFormat['message'] = trans('messages.auth.email_password_incorrect');
                return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
            $user = JWTAuth::setToken($token)->toUser();
            if ($user->access_token != null && $user->access_token != '') {
                try {
                    JWTAuth::setToken($user->access_token)->invalidate();
                } catch (\Exception/*\Tymon\JWTAuth\Exceptions\TokenBlacklistedException*/ $e) {

                }
            }
            $this->user->update([
                'access_token' => $token
            ], $user->id);
            DB::commit();
            $dataUser = new UserResource($user);
            $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
            $apiFormat['data']['token'] = $token;
            $apiFormat['data']['user'] = $dataUser;
            $apiFormat['message'] = trans('messages.auth.login_successful');
        } catch (Exception $e) {
            DB::rollBack();
            $apiFormat['message'] = trans('messages.an_error_occurred');
        }
        return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Đăng ký bằng email
     *
     * @param Request $request
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function register(Request $request) {
        $apiFormat['status'] = Config::get('constants.STATUS.INACTIVE');
        $validator = Validator::make($request->only([
            'email',
            'password',
            'confirm_password'
        ]), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password'
        ], [
//            'email.required' => 'Email là bắt buộc',
//            'email.email' => 'Email không hợp lệ',
//            'email.unique' => 'Email đã được sử dụng',
//            'password.required' => 'Mật khẩu là bắt buộc',
//            'password.min' => 'Mật khẩu tối thiểu 8 ký tự',
//            'confirm_password.required_with' => 'Xác nhận mật khẩu là bắt buộc',
//            'confirm_password.same' => 'Xác nhận mật khẩu không khớp'
        ]);
        if ($validator->fails()) {
            $apiFormat['message'] = $validator->errors()->first();
            return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }
        try {
            DB::beginTransaction();
            $email = $request->input('email');
            $user = $this->user->create([
                'email' => $email,
                'password' => Hash::make($request->input('password')),
                'status' => Config::get('constants.STATUS.ACTIVE'),
                'type' => Config::get('constants.TYPE_USER.MEMBER'),
            ]);
            $token = JWTAuth::fromUser($user);
            $this->user->update([
                'access_token' => $token
            ], $user->id);
            $user = $this->user->find($user->id);
            DB::commit();
            $dataUser = new UserResource($user);
            $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
            $apiFormat['data']['token'] = $token;
            $apiFormat['data']['user'] = $dataUser;
            $apiFormat['message'] = trans('messages.auth.sign_up_successful');
        } catch (\Exception $e) {
            DB::rollBack();
            $apiFormat['message'] = trans('messages.an_error_occurred');
        }
        return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Lấy profile của user
     *
     * @param Request $request
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function profile(Request $request) {
        $apiFormat['status'] = Config::get('constants.STATUS.INACTIVE');
        try {
            $user = JWTAuth::parseToken()->toUser();
            $dataUser = new UserResource($user);
            $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
            $apiFormat['data'] = $dataUser;
            $apiFormat['message'] = trans('messages.auth.success');
        } catch (Exception $e) {
            $apiFormat['message'] = trans('messages.an_error_occurred');
        }
        return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Đăng xuất
     *
     * @param
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function logout() {
        $apiFormat['status'] = Config::get('constants.STATUS.INACTIVE');
        try {
            $token = JWTAuth::getToken();
            JWTAuth::setToken($token)->invalidate();
            $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
            $apiFormat['message'] = trans('messages.auth.logout_successful');
        } catch (Exception $e) {
            $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
            $apiFormat['message'] = trans('messages.auth.logout_successful');
        }
        return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Đăng nhập với Facebook
     *
     * @param
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function loginWithFacebook(Request $request) {
        $apiFormat = [];
        $apiFormat['status'] = \Config::get('constants.STATUS.INACTIVE');
        $validator = \Validator::make($request->only([
            'access_token'
        ]), [
            'access_token' => 'required',
        ], [
//            'access_token.required' => 'Facebook Access_token Required'
        ]);
        if ($validator->fails()) {
            $message = $validator->errors()->first();
            $apiFormat['message'] = $message;
            return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }
        try {
            $url = 'https://graph.facebook.com/v3.2/me';
            $client = new Client();
            try {
                $clientRequest = $client->get($url, [
                    'query' => [
                        'fields' => 'email,name',
                        'access_token' => $request->input('access_token')
                    ]
                ]);
                $httpCode = $clientRequest->getStatusCode();
                $userFB = json_decode($clientRequest->getBody());
                if (isset($userFB->error)) {
                    if ($userFB->error->code == 190) {
                        $apiFormat['message'] = $userFB->error->code . ' - ' . trans('messages.auth.access_token_expired');
                    } else {
                        $apiFormat['message'] = $userFB->error->code . ' - ' . $userFB->error->message;
                    }
                    return response()->json($apiFormat, 200, ['Content-type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
                }
            } catch (RequestException $requestException) {
                $httpCode = $requestException->getCode();
            }
            if ($httpCode != 200) {
                $apiFormat['message'] = trans('messages.auth.request_facebook_error');
                return response()->json($apiFormat, 200, ['Content-type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
            if (isset($userFB->id)) {
                DB::beginTransaction();
                // coding logic here
                // coding logic here
                // coding logic here
                // coding logic here
                // coding logic here
                DB::commit();
                $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
                $apiFormat['message'] = trans('messages.auth.login_successful');
            } else {
                $apiFormat['status'] = Config::get('constants.STATUS.INACTIVE');
                $apiFormat['message'] = trans('messages.auth.login_invalid');
                return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $apiFormat['message'] = trans('messages.an_error_occurred');
        }
        return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Đăng nhập với Google
     *
     * @param
     *
     * @return mixed response json
     *
     * @author SD
     */
    public function loginWithGoogle(Request $request) {
        $apiFormat = [];
        $apiFormat['status'] = \Config::get('constants.STATUS.INACTIVE');
        $validator = \Validator::make($request->only([
            'access_token'
        ]), [
            'access_token' => 'required',
        ], [
//            'access_token.required' => 'Google Access_token Required'
        ]);
        if ($validator->fails()) {
            $message = $validator->errors()->first();
            $apiFormat['message'] = $message;
            return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }
        try {
            $url = 'https://www.googleapis.com/oauth2/v2/userinfo';
            $client = new Client();
            try {
                $clientRequest = $client->get($url, [
                    'query' => [
                        'access_token' => $request->input('access_token')
                    ]
                ]);
                $userGG = json_decode($clientRequest->getBody());
                $httpCode = $clientRequest->getStatusCode();
                if (isset($userGG->error)) {
                    $apiFormat['message'] = trans('messages.an_error_occurred');
                    return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
                }
            } catch (RequestException $requestException) {
                $httpCode = $requestException->getCode();
            }
            if ($httpCode != 200) {
                $apiFormat['message'] = trans('messages.auth.request_google_error');
                return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
            if (isset($userGG->id)) {
                DB::beginTransaction();
                // coding logic here
                // coding logic here
                // coding logic here
                // coding logic here
                // coding logic here
                DB::commit();
                $apiFormat['status'] = Config::get('constants.STATUS.ACTIVE');
                $apiFormat['message'] = trans('messages.auth.login_successful');
            } else {
                $apiFormat['status'] = Config::get('constants.STATUS.INACTIVE');
                $apiFormat['message'] = trans('messages.auth.login_invalid');
                return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $apiFormat['message'] = trans('messages.an_error_occurred');
        }
        return response()->json($apiFormat, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
