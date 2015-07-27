<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;
use Auth;

class PasswordController extends Controller {

    /**
     * Create a new password controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\PasswordBroker  $passwords
     * @return void
     */
    public function __construct(PasswordBroker $passwords)
    {
        $this->passwords = $passwords;

        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */
    public function getEmail()
    {
      return view('auth.password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postEmail(Request $request)
    {
      $this->validate($request, ['email' => 'required|email']);

      $response = $this->passwords->sendResetLink($request->only('email'), function($m)
      {
          $m->subject('Your Password Reset Link');
          $m->from('administraor@blop.fr', 'administrator');
      });

      switch ($response)
      {
        case PasswordBroker::RESET_LINK_SENT:
          return redirect()->back()->with('status', trans($response));

        case PasswordBroker::INVALID_USER:
          return redirect()->back()->withErrors(['email' => trans($response)]);
      }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getReset($token)
    {
        return view('auth.reset')->with('token', $token);
    }

    /**
     * Reset the given user's password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
          'token' => 'required',
          'email' => 'required|email',
          'password' => 'required|confirmed',
        ]);

        $credentials = $request->only(
          'email', 'password', 'password_confirmation', 'token'
        );

        $response = $this->passwords->reset($credentials, function($user, $password)
        {
          $user->password = bcrypt($password);

          $user->save();

          Auth::login($user);
        });

        switch ($response)
        {
          case PasswordBroker::PASSWORD_RESET:
            return redirect('/');

          default:
            return redirect()->back()
                  ->withInput($request->only('email'))
                  ->withErrors(['email' => trans($response)]);
        }
    }

}
