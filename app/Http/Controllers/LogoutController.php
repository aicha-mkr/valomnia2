<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class LogoutController extends Controller
{
    public function logout(Request $request)
    {
  /** @var \App\Models\User $user */
        $user = $request->user();
        $user-> currentAccesToken()->delete();
        return response('',204);    }
}