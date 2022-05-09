<?php

namespace App\Core\Auth\Controllers;

use App\Core\Auth\Requests\LoginAuthRequest;
use App\Core\Auth\Traits\Auth\AuthenticatesUsersTrait;
use App\Core\Auth\Traits\Auth\ThrottlesLoginTrait;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Cirelramos\ErrorNotification\Services\CatchNotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{

    use AuthenticatesUsersTrait;
    use ThrottlesLoginTrait;

    /**
     * @var int
     */
    public $maxAttempts = 5; // change to the max attemp you want.

    /**
     * @var int
     */
    public $decayMinutes = 5; // change to the minutes you want

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
    }

    /**
     * @param LoginAuthRequest $loginAuthRequest
     * @return bool|JsonResponse|string|null
     */
    public function login(LoginAuthRequest $loginAuthRequest)
    {
        try {
            $loginAuthRequest = $this->fakeRequestUsername($loginAuthRequest);
            $this->validateLogin($loginAuthRequest);

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($loginAuthRequest)) {
                $this->fireLockoutEvent($loginAuthRequest);
                $this->sendLockoutResponse($loginAuthRequest);
            }
            if ($this->attemptLogin($loginAuthRequest)) {
                $this->clearLoginAttempts($loginAuthRequest);
                $token = $this->createToken($loginAuthRequest);
                if ($loginAuthRequest->remember) {
                    $token->token->expires_at = Carbon::now()->minute(1);
                }
                $token->token->save();

                return $this->getDataLogin($token, $loginAuthRequest);
            }
            $this->incrementLoginAttempts($loginAuthRequest);
            $this->sendFailedLoginResponse($loginAuthRequest);
        } catch (Exception $exception) {
            $message =
                json_decode($exception->getMessage()) !== null ? json_decode(
                    $exception->getMessage()
                ) : $exception->getMessage();
            $code    =
                (int)($exception->getCode() !== 0 && $exception->getCode() < 530 && $exception->getCode(
                ) > 100 ? $exception->getCode()
                    : 500);

            CatchNotificationService::error([
                                                'exception' => $exception,
                                                'usersId'   => Auth::id(),
                                            ]);

            return $this->errorCatchResponse($exception, $message, $code);
        }

    }

    /**
     * @param LoginAuthRequest $loginAuthRequest
     * @return LoginAuthRequest|Request
     */
    public function fakeRequestUsername(LoginAuthRequest $loginAuthRequest): LoginAuthRequest|Request
    {
        $userName = $loginAuthRequest->username;
        if ($userName !== null) {
            $loginAuthRequest               = $loginAuthRequest->all();
            $loginAuthRequest[ 'email' ]    = $userName;
            $loginAuthRequest[ 'remember' ] = false;
            $loginAuthRequest               = Request::create('', 'POST', $loginAuthRequest);
        }
        return $loginAuthRequest;

    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function createToken(Request $request): mixed
    {
        $user = Auth::user();

        return $user->createToken('Personal Access Token');
    }

    /**
     * @param $token
     * @param $loginAuthRequest
     * @return false|string
     */
    public function getDataLogin($token, $loginAuthRequest): bool|string
    {
        $user = Auth::user();
        $data = [
            'id'                => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'access_token'      => $token->accessToken,
            'token_type'        => 'Bearer',
            'user_id_role_real' => $user->id_role,
            'create_at'         => $token->token->created_at,
            'expires_at'        => $token->token->expires_at,
        ];
        if ($loginAuthRequest->username !== null) {
            return json_encode($data);
        }

        return $this->successResponse($data, __('User logged'));
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return $this->successResponse([], __('Successfully logged out'), Response::HTTP_OK);

    }
}
