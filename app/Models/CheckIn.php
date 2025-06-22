<?php

namespace App\Models;

use Illuminate\Support\Facades\Log; // Import Log facade
use App\Models\User; // Import User model
use App\Models\ApiCall;

class CheckIn
{
  public static function ListCheckIns($data)
  {
    $api_call = new ApiCall(true, $data["user_id"]);
    $url_api = str_replace("organisation", $data["organisation"], env('URL_API', 'https://organisation.valomnia.com'));

    $query_params = [
      'employeeReference' => $data['employeeReference'],
      'max' => $data['max'] ?? 5,
      'offset' => $data['offset'] ?? 0,
      'order' => 'desc',
      'startDate_gte' => $data['startDate'],
      'startDate_lte' => $data['endDate']
    ];

    Log::info("Appel API check-ins avec paramètres: " . json_encode($query_params));

    $api_response = json_decode(
      $api_call->GetResponse(
        $url_api . '/api/v2.1/checkIns',
        'GET',
        $query_params,
        false,
        "JSESSIONID=" . $data["cookies"]
      ),
      true
    );

    if (isset($api_response["error"]) && $api_response["error"] == "no_credentials") {
      Log::info("Erreur no_credentials, tentative de rafraîchissement du token...");
      $refresh_token_response = User::RefreshAccessToken($data["user_id"]); // Fixed typo

      if (isset($refresh_token_response["status"]) &&
        $refresh_token_response["status"] == 200 &&
        isset($refresh_token_response["data"]["cookies"])) {

        $cookies = $refresh_token_response["data"]["cookies"] ?? '';
        Log::info("Nouveau JSESSIONID obtenu: {$cookies}");
        $api_response = json_decode(
          $api_call->GetResponse(
            $url_api . '/api/v2.1/checkIns',
            'GET',
            $query_params,
            false,
            "JSESSIONID=" . $cookies
          ),
          true
        );
      } else {
        Log::error("Échec du rafraîchissement du token: " . json_encode($refresh_token_response));
        return ['error' => 'token_refresh_failed']; // Return error to propagate failure
      }
    }

    Log::info("Réponse API check-ins: " . json_encode($api_response));
    return $api_response;
  }
}
