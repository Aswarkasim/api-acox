<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    function index()
    {
        $user_id = request('user_id');
        $user = User::find($user_id);
        // $user = User::get();

        if ($user) {
            return responserSuccess('user', '200', $user);
        } else {
            return badResponse('user');
        }
    }
}
