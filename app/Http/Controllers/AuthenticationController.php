<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; // Import Log for logging

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Exception;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $response = ["status" => 400, "error" => ""]; 
        try {
            $credentials = $request->all();
    
            // Recherche d'utilisateur
            $response_found = User::FindUser($credentials);
            $user = $response_found['data']["user"] ?? null;
    
            if ($response_found["status"] == 200 && isset($user) && isset($user->token)) {
                // Utilisateur existant
                Session::put('user_id', $user['id']);
                Session::put('user_data', $user);
    
                // Handle response based on request type
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'success', 'user' => $user]);
                }
    
                return redirect()->route('dashboard'); // Redirect to dashboard
            } else {
                // Essayer de connecter l'utilisateur
                $response = User::UserLogin($credentials);
                if ($response["status"] == 200) {
                    $user_data = $response["data"];
                    $cookies = $response["cookies"] ?? '';
                    $user_data["cookies"] = $cookies;
                    $user_data["organisation"] = $credentials["organisation"];
                    $user_data["password"] = $credentials["password"];
    
                    // Mettre à jour ou créer l'utilisateur
                    $response_created = User::UpdateOrCreated($user_data);
                    if ($response_created["status"] == 200) {
                        $user = $response_created["user"];
                        
                        // Enregistrement des données dans la session
                        Session::put('user_id', $user['id']);
                        Session::put('user_data', $user);
                        Log::info('User logged in:', ['user_id' => $user->id]);

    
                        // Handle response based on request type
                        if ($request->expectsJson()) {
                            return response()->json(['status' => 'success', 'user' => $user]);
                        }
    
                        return redirect()->route('dashboard'); // Redirect to dashboard
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
    
        // Handle error response
        if ($request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => $response['error']], 400);
        }
    
        return redirect()->route('pages-misc-error')->withErrors($response['error']);
    }
    public function showDashboard()
    {
        return view('content.dashboard.dashboards-analytics'); // Show the dashboard view
    }

    public function showLoginForm()
    {
        return view('content.authentications.auth-login-basic'); // Remplacez par votre vue
    }

    public function checkSession()
    {
        $userId = Session::get('user_id');
        return response()->json(['user_id' => $userId]);
    }
}