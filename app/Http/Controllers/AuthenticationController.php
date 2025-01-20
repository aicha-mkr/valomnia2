<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $response = ["status" => 400, "error" => ""];

        try {
            $credentials = $request->all();
            $response_found = User::FindUser($credentials);
            $user = $response_found['data']["user"] ?? null;

            if ($response_found["status"] == 200 && $user) {
                // Existing user
                Session::put('user_id', $user->id);
                Session::put('user_data', $user);
                Auth::login($user);  // Ensure the user is logged in

                Log::info('User logged in:', ['user_id' => $user->id]);

                return $this->handleResponse($request, ['status' => 'success', 'user' => $user]);
            } else {
                $response = User::UserLogin($credentials);

                if ($response["status"] == 200) {
                    $user_data = $response["data"];
                    $user_data["organisation"] = $credentials["organisation"];
                    $user_data["password"] = $credentials["password"];
                    
                    // Retrieve and assign cookies
                    $cookies = $response["cookies"] ?? '';
                    $user_data["cookies"] = $cookies;  // Add cookies to user data

                    $response_created = User::UpdateOrCreated($user_data);

                    if ($response_created["status"] == 200) {
                        $user = $response_created["user"];
                        Log::info('User logged in:', ['user_id' => $user['id']]);
                        Session::put('user_id', $user['id']);
                        Auth::loginUsingId($user['id']); // Ensure the user is logged in

                        return $this->handleResponse($request, ['status' => 'success', 'user' => $user]);
                    } else {
                        $response = ["status" => 400, "error" => $response_created["error"]];
                    }
                } else {
                    $response = ["status" => 401, "error" => 'Invalid credentials'];
                }
            }
        } catch (Exception $ex) {
            $response = ["status" => 400, "error" => $ex->getMessage()];
        }

        return $this->handleResponse($request, ['status' => 'error', 'message' => $response['error']], 400);
    }

    private function handleResponse(Request $request, $data, $status = 200)
    {
        if ($request->expectsJson()) {
            return response()->json($data, $status);
        } else {
            if ($status === 400 || $status === 401) {
                return redirect()->route('pages-misc-error')->withErrors($data['message']);
            } else {
                return redirect()->route('dashboard');
            }
        }
    }

    public function showDashboard()
    {
        if (Auth::check()) {
            return view('content.dashboard.dashboards-analytics');
        } else {
            return redirect()->route('login');
        }
    }

    public function showLoginForm()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function checkSession()
    {
        $userId = Session::get('user_id');
        return response()->json(['user_id' => $userId]);
    }
}