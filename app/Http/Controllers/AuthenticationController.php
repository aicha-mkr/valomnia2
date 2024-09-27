<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'organisation' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $credentials = $request->only('organisation', 'email', 'password');
            $response_found = User::FindUser($credentials);
            $user = $response_found['data']['user'] ?? null;

            // Check if the request expects JSON
            if ($request->wantsJson()) {
                if ($response_found["status"] == 200 && isset($user) && isset($user->token)) {
                    return response()->json([
                        'message' => 'User found',
                        'user' => $user
                    ]);
                } else {
                    // Attempt login and check response
                    $response = User::UserLogin($credentials);
                    if ($response["status"] == 200) {
                        // User login successful
                        $user_data = $response["data"];
                        $cookies = $response["cookies"] ?? '';
                        $user_data["cookies"] = $cookies;
                        $user_data["organisation"] = $credentials["organisation"];
                        $user_data["password"] = $credentials["password"];
                
                        $response_created = User::UpdateOrCreated($user_data);
                        
                        if ($response_created["status"] == 200) {
                            return response()->json([
                                'message' => 'Login successful',
                                'user' => $user_data
                            ]);
                        } else {
                            return response()->json(['error' => $response_created["error"]], 500);
                        }
                    } elseif ($response["status"] == 401) {
                        return response()->json(['error' => 'Invalid credentials'], 401);
                    } else {
                        return response()->json(['error' => 'An error occurred during login.'], 500);
                    }
                }
            } else {
                // Handle web requests
                if ($response_found["status"] == 200 && isset($user) && isset($user->token)) {
                    return view('content.dashboard.dashboards-analytics');
                } else {
                    $response = User::UserLogin($credentials);
                    if ($response["status"] == 200) {
                        $user_data = $response["data"];
                        $cookies = $response["cookies"] ?? '';
                        $user_data["cookies"] = $cookies;
                        $user_data["organisation"] = $credentials["organisation"];
                        $user_data["password"] = $credentials["password"];
                
                        $response_created = User::UpdateOrCreated($user_data);

                        if ($response_created["status"] == 200) {
                            return view('content.dashboard.dashboards-analytics');
                        } else {
                            return view('content.pages.pages-misc-error')->withErrors(['error' => $response_created["error"]]);
                        }
                    } elseif ($response["status"] == 401) {
                        return view('content.pages.pages-misc-error')->withErrors(['error' => 'Invalid credentials']);
                    } else {
                        return view('content.pages.pages-misc-error')->withErrors(['error' => 'An error occurred during login.']);
                    }
                }
            }
        } catch (Exception $ex) {
            // Handle exception for both JSON and web
            if ($request->wantsJson()) {
                return response()->json(['error' => $ex->getMessage()], 500);
            } else {
                return view('content.pages.pages-misc-error')->withErrors(['error' => $ex->getMessage()]);
            }
        }
    }

    public function showLoginForm()
    {
        return view('content.authentications.auth-login-basic');
    }
}