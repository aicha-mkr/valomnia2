<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $response = ["status" => 400, "error" => ""];

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

            if ($response_found["status"] == 200 && isset($user) && isset($user->token)) {
                // Valid user found
                $response = ["status" => 200, "data" => $user];
            } else {
                // Attempt login and check response
                $response = User::UserLogin($credentials);
                if ($response["status"] == 200) {
                    // Only store the user if login is successful
                    $user_data = $response["data"];
                    $cookies = $response["cookies"] ?? '';
                    $user_data["cookies"] = $cookies;
                    $user_data["organisation"] = $credentials["organisation"];
                    $user_data["password"] = $credentials["password"];
            
                    $response_created = User::UpdateOrCreated($user_data);
                    
                    if ($response_created["status"] == 200) {
                        $user = $response_created["user"];
                        $response = ["status" => 200, "data" => $user];
                    } else {
                        $response = ["status" => 400, "error" => $response_created["error"]];
                    }
                } else {
                    // Invalid credentials
                    $response = ["status" => 401, "error" => 'Invalid credentials'];
                }
            
            }
        } catch (Exception $ex) {
            $response = ["status" => 400, "error" => $ex->getMessage()];
        }

        return response()->json($response);
    }

    public function showLoginForm()
    {
        return view('content.authentications.auth-login-basic');
    }
}