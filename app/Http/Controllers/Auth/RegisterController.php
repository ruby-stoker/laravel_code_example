<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserRegistrationRequest;
use App\Services\ActivationService;
use App\Services\UserRegistrationService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/home';

    /**
     * @var UserRegistrationService
     */
    private $registrationService;
    /**
     * @var ActivationService
     */
    private $activationService;

    /**
     * Create a new controller instance.
     *
     * @param UserRegistrationService $registrationService
     */
    public function __construct(UserRegistrationService $registrationService, ActivationService $activationService)
    {
        $this->middleware('guest');
        $this->registrationService = $registrationService;
        $this->activationService = $activationService;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  UserRegistrationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserRegistrationRequest $request)
    {
        $user = $this->registrationService->createUser($request->validated());

        return redirect()->to('login')->with('status', trans('auth.confirm_email'));
    }

    /**
     * @param $token
     * @return mixed
     */
    public function activateUser($token)
    {
        if ($user = $this->activationService->activateUser($token)) {
            auth()->login($user);
            return $this->authenticated();
        }
        abort(404);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function authenticated()
    {
        return redirect()->to('home')->with('status', __('auth.activation_confirm'));
    }
}
