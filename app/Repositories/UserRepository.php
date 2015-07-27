<?php

namespace App\Repositories;

use App\User;

class UserRepository {

    /**
     * Store a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\User
     */
    public function store($request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }

}
