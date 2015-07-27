<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Auth;

class RedirectIfAuthenticated {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::check())
		{
			return new RedirectResponse(url('/home'));
		}

		return $next($request);
	}

}
