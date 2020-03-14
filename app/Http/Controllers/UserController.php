<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    /**
     * Return the current user information in json format.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getMe()
    {
        return json(['user' => request()->user()]);
    }
}
