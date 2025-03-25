<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
{

    try {
        $credentials = $request->all();
        $response_found = User::FindUser($credentials);
        $user = $response_found['data']["user"] ?? null;

        if ($response_found["status"] == 200 && isset($user) && isset($user->token)) {
            // Use Laravel's auth system
            Auth::loginUsingId($user->id); // Log the user in and store the user ID in the session

            // Optional: You can still use custom session variables
            Session::put('token_user', $response_found['data']["token"]);
            Session::put('user', $response_found['data']["user"]);

            $roles = explode(",", $user->role);

            // Redirect based on roles
            if (in_array("SUPER_ADMIN", $roles)) {
                Session::put('isSuperAdmin', true);
                Session::forget('isOrganisation');
                return redirect()->route('dashboard-admin');
            } else {
                Session::put('isOrganisation', true);
                Session::forget('isSuperAdmin');
                return redirect()->route('dashboard-organisation');
            }
        } else {
            // Invalid credentials or user not found
            Log::warning('Failed login attempt - User not found.', ['credentials' => $credentials]);
            return redirect()->route('auth-login')->with('error', 'Invalid credentials!');
        }
    } catch (Exception $ex) {
        // Log error and redirect
        Log::error('Exception during login.', ['message' => $ex->getMessage()]);
        return redirect()->route('auth-login')->with('error', 'Something went wrong!');
    }
}

    public function index()
    {
        if (session()->has('user') && session()->has('token_user')) {
            if (session()->has('isOrganisation')) {
                return redirect()->route('dashboard-organisation');
            } else {
                return redirect()->route('dashboard-admin');
            }
        }
        return view('content.authentications.login');
    }

    public function logout()
    {
        Session::forget('isSuperAdmin');
        Session::forget('token_user');
        Session::forget('user');
        Session::forget('isOrganisation');
        return redirect()->route('auth-login');
    }
}