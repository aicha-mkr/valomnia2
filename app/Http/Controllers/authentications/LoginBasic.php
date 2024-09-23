<?php

namespace App\Http\Controllers\Authentications; // Assurez-vous que l'espace de noms est correct

use App\Http\Controllers\Controller; // Importer le contrôleur de base
use Illuminate\Http\Request;
use App\Models\User;
use Exception; // Importation d'Exception pour la gestion des erreurs

class LoginBasic extends Controller
{
    public function login(Request $request)
    {
        $response = ["status" => 400, "error" => ""]; // Initialisation de la réponse

        try {
            $credentials = $request->all();

            $response_found = User::findUser($credentials); // Vérifiez la méthode
            $user = $response_found['data']['user'] ?? null;

            if ($response_found["status"] == 200 && isset($user) && isset($user->token)) {
                // Si l'utilisateur est trouvé et a un token
                $response = ["status" => 200, "data" => $user];
            } else {
                // Tentative de connexion
                $response = User::UserLogin($credentials);

                if ($response["status"] == 200) {
                    $user_data = $response["data"];
                    $cookies = $response["cookies"] ?? '';
                    $user_data["cookies"] = $cookies;
                    $user_data["organisation"] = $credentials["organisation"] ?? ''; // Utilisation de coalescence null
                    $user_data["password"] = $credentials["password"];
                    
                    // Mise à jour ou création de l'utilisateur
                    $response_created = User::UpdateOrCreated($user_data);

                    if ($response_created["status"] == 200) {
                        $user = $response_created["user"];
                        $response = ["status" => 200, "data" => $user];
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

        return response()->json($response);
    }
}