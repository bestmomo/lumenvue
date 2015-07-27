<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Auth;
use App\User;

class AuthController extends Controller {

    /**
     * Create a new authentication controller instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout', 'getLog']]);
    }

    /**
     * Get auth
     *
     * @return json
     */
    public function getLog()
    {
        return ['auth' => Auth::check()];
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember')))
        {
            return ['result' => 'success'];
        }

        return ['result' => 'fail'];
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        return ['result' => 'success'];
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Repositories\UserRepository
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request, UserRepository $userRepository)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = $userRepository->store($request);

        Auth::login($user);

        return redirect('/');
    }

}
