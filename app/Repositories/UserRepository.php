<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    /**
     * User model.
     *
     * App\Models\User
     */
    protected $user;

    /**
     * Create new instance of user repository.
     *
     * @param User $user User model
     */
    public function __construct(User $user)
    {
        parent::__construct($user);

        $this->user = $user;
    }

    /**
     * Store the data in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return boolean
     */
    // public function store($request)
    // {
    //     return $this->user->create([
    //         'name'          => $request->name,
    //         'email'         => $request->email,
    //         'password'      => bcrypt($request->password)
    //     ]);
    // }
}
