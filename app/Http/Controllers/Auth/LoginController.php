<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
  public function login(LoginRequest $request)
  {
    try {
      // Use validated data from LoginRequest
      $credentials = $request->validated();

      // Check if user exists
      $response_found = User::FindUser($credentials);
      if ($response_found["status"] == 200 && isset($response_found["data"]["user"]) && isset($response_found["data"]["token"])) {
        $user = $response_found["data"]["user"];

        // Log the user in using Laravel's Auth system
        Auth::login($user);

        // Store additional session data
        Session::put('token_user', $response_found["data"]["token"]);
        Session::put('user', $user);

        $roles = explode(",", $user->role);
        if (in_array("SUPER_ADMIN", $roles)) {
          Session::put('isSuperAdmin', true);
          Session::forget('isOrganisation');
          return redirect()->route('dashboard-admin');
        } else {
          Session::put('isOrganisation', true);
          Session::forget('isSuperAdmin');
          return redirect()->route('dashboard-organisation');
        }
      }

      // If user not found, attempt login
      $response = User::UserLogin($credentials);
      if ($response["status"] == 200 && isset($response["data"]["user"])) {
        $user_data = $response["data"];
        $user_data["cookies"] = $response["cookies"] ?? '';
        $user_data["organisation"] = $credentials["organisation"];
        $user_data["password"] = $credentials["password"];

        // Create or update user
        $response_created = User::UpdateOrCreated($user_data);
        if ($response_created["status"] == 200 && isset($response_created["user"])) {
          $user = $response_created["user"];

          // Log the user in using Laravel's Auth system
          Auth::login($user);

          // Store additional session data
          Session::put('user', $user);

          $roles = explode(",", $user->role);
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
          $error_message = $response_created["error"] ?? 'Failed to create or update user';
          Log::error('UpdateOrCreated failed:', $response_created);
          return redirect()->route('auth-login')->with('error', $error_message);
        }
      } else {
        $error_message = $response["error"] ?? 'Invalid credentials';
        Log::error('UserLogin failed:', $response);
        return redirect()->route('auth-login')->with('error', $error_message);
      }
    } catch (\Exception $ex) {
      Log::error('Login error:', ['exception' => $ex->getMessage()]);
      return redirect()->route('auth-login')->with('error', 'An error occurred: ' . $ex->getMessage());
    }
  }

  public function index()
  {
    if (Auth::check()) {
      return session()->has('isOrganisation')
        ? redirect()->route('dashboard-organisation')
        : redirect()->route('dashboard-admin');
    }
    return view('content.authentications.login');
  }

  public function logout()
  {
    Auth::logout(); // Clear the authenticated user
    Session::flush(); // Clear all session data
    return redirect()->route('auth-login');
  }
}
