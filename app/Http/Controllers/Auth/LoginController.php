<?php

namespace App\Http\Controllers\Auth;

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
                // Log success message
                Log::info('User logged in successfully.', ['email' => $user->email]);

                Session::put('token_user', $response_found['data']["token"]);
                Session::put('user', $response_found['data']["user"]);
                $roles = explode(",", $user->role);

                if (array_search("SUPER_ADMIN", $roles) !== false) {
                    Session::put('isSuperAdmin', true);
                    Session::forget('isOrganisation');
                    return redirect()->route('dashboard-admin');
                } else {
                    Session::put('isOrganisation', true);
                    Session::forget('isSuperAdmin');
                    return redirect()->route('dashboard-organisation');
                }
            } else {
                Log::warning('Failed login attempt - User not found.', ['credentials' => $credentials]);
                
                $response = User::UserLogin($credentials);
                if ($response["status"] == 200) {
                    $user_data = $response["data"];
                    $cookies = $response["cookies"] ?? '';
                    $user_data["cookies"] = $cookies;
                    $user_data["organisation"] = $credentials["organisation"];
                    $user_data["password"] = $credentials["password"];
                    $roles = $user_data["user"]["role"];

                    $response_created = User::UpdateOrCreated($user_data);
                    if ($response_created["status"] == 200) {
                        $user = $response_created["user"];
                        $roles = explode(",", $user->role);
                        
                        // Log success for user creation
                        Log::info('User created and logged in.', ['email' => $user->email]);

                        if (array_search("SUPER_ADMIN", $roles) !== false) {
                            Session::put('isSuperAdmin', true);
                            Session::forget('isOrganisation');
                            return redirect()->route('dashboard-admin');
                        } else {
                            Session::put('isOrganisation', true);
                            Session::forget('isSuperAdmin');
                            return redirect()->route('dashboard-organisation');
                        }
                    } else {
                        Log::error('Error creating user.', ['error' => $response_created["error"]]);
                        return redirect()->route('auth-login')->with('error', $response_created["error"]);
                    }
                } else {
                    Log::warning('Invalid credentials for login attempt.', ['credentials' => $credentials]);
                    return redirect()->route('auth-login')->with('error', 'Invalid credentials');
                }
            }
        } catch (Exception $ex) {
            Log::error('Exception during login.', ['message' => $ex->getMessage()]);
            return redirect()->route('auth-login')->with('error', $ex->getMessage());
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