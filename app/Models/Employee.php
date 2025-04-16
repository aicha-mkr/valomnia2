<?php
// app/Models/Employee.php

namespace App\Models;

use App\Models\ApiCall;

class Employee
{
  public static function ListEmployees($data)
  {
    $api_call = new ApiCall(true, $data["user_id"]);
    $url_api = str_replace("organisation", $data["organisation"], env('URL_API', 'https://organisation.valomnia.com'));

    $api_response = json_decode(
      $api_call->GetResponse(
        $url_api . '/api/v2.1/employees',
        'GET',
        array(),
        false,
        "JSESSIONID=" . $data["cookies"]
      ),
      true
    );

    if (isset($api_response["error"]) && $api_response["error"] == "no_credentials") {
      $refresh_token_response = User::RefreshAccessToken($data["user_id"]);

      if (isset($refresh_token_response["status"]) &&
        $refresh_token_response["status"] == 200 &&
        isset($refresh_token_response["data"]["cookies"])) {

        $cookies = $refresh_token_response["data"]["cookies"] ?? '';
        $api_response = json_decode(
          $api_call->GetResponse(
            $url_api . '/api/v2.1/employees',
            'GET',
            array(),
            false,
            "JSESSIONID=" . $cookies
          ),
          true
        );
      }
    }

    return $api_response;
  }
}
