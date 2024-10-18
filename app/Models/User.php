<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Exception;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->makeAllAttributesFillable();
    }

    protected function makeAllAttributesFillable()
    {
        $this->fillable = $this->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->getTable());
    }

    public function recapitulatifs()
    {
        return $this->hasMany(Recapitulatif::class);
    }

    public static function FindUser($data)
    {
        $response = ["status" => 400, "error" => ""];
        try {
            $user = User::where("email", $data['email'])
                        ->where("organisation", $data["organisation"])
                        ->first();
            if (!$user) {
                $response = ["status" => 400, "error" => "NOTFOUND"];
            } else {
                $response = [
                    "status" => 200,
                    "data" => [
                        "user" => $user,
                        "token" => $user->token,
                    ],
                ];
            }
        } catch (Exception $ex) {
            $response = ["status" => 400, "error" => $ex->getMessage()];
        }
        return $response;
    }

    public static function getAccessToken($id)
    {
        $response = ["status" => 400, "error" => ""];
        try {
            $user = User::find($id);
            if ($user) {
                if (empty($user->token) || empty($user->cookies)) {
                    $user = User::RefreshAccessToken($user->id);
                }
                $response = ["status" => 200, "data" => $user->token];
            } else {
                $response = ["status" => 400, "error" => "UserNotFound"];
            }
        } catch (Exception $ex) {
            $response = ["status" => 400, "error" => $ex->getMessage()];
        }
        return $response;
    }

    public static function RefreshAccessToken($id)
    {
        $response = ["status" => 400, "error" => ""];
        try {
            $user = User::find($id);
            if ($user) {
                $credentials = [
                    "email" => $user->email,
                    "password" => $user->password_valomnia,
                    "organisation" => $user->organisation,
                ];

                $response = User::UserLogin($credentials);
                if ($response["status"] == 200) {
                    $userData = $response["data"];
                    $cookies = $response["cookies"] ?? '';
                    $userData["cookies"] = $cookies;
                    $userData["organisation"] = $credentials["organisation"];
                    return User::UpdateOrCreated($userData);
                } else {
                    $response = ["status" => 400, "error" => "UserInvalid"];
                }
            } else {
                $response = ["status" => 400, "error" => "UserNotFound"];
            }
        } catch (Exception $ex) {
            $response = ["status" => 400, "error" => $ex->getMessage()];
        }
        return $response;
    }

    public static function UserLogin($data)
    {
        $response = ["status" => 400, "error" => ""];
        try {
            $api_call = new ApiCall(false);
            $url_api = str_replace("organisation", $data["organisation"], env('URL_API','https://organisation.valomnia.com'));
            $data_request = [
                "j_username" => $data["email"],
                "j_password" => $data["password"],
                "ajax" => "true",
            ];
            $api_response = $api_call->GetResponse($url_api . '/j_spring_security_check', 'GET', $data_request, true);
            if (strpos($api_response, "302") !== false && strpos($api_response, "login/ajaxSuccess") !== false) {
                preg_match('/^Set-Cookie:\s*JSESSIONID=([^;]+)/mi', $api_response, $matches);
                $jsessionid = $matches[1] ?? null;

                if ($jsessionid) {
                    $api_response = json_decode($api_call->GetResponse($url_api . "/login/ajaxSuccess", 'GET', [], false, "JSESSIONID=$jsessionid"), true);
                    $response = [
                        "status" => 200,
                        "data" => $api_response,
                        "cookies" => $jsessionid,
                    ];
                } else {
                    $response = ["status" => 400, "error" => "LoginFailed !"];
                }
            } else {
                $response = ["status" => 400, "error" => "LoginFailed !"];
            }
        } catch (Exception $ex) {
            $response = ["status" => 400, "error" => $ex->getMessage()];
        }
        return $response;
    }

    public static function UpdateOrCreated($data)
    {
        $response = ["status" => 400, "error" => ""];
        try {
            $token = $data["token"];
            $organisation_name = $data["organisation"];
            $user_data = $data["user"];

            $user = User::where("email", $user_data['email'])
                        ->where("organisation", $organisation_name)
                        ->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'organisation' => $organisation_name,
                    'email' => $user_data['email'],
                    'name' => $user_data['firstName'] . " " . $user_data['lastName'],
                    'token' => $token,
                    'password_valomnia' => $data['password'],
                    'cookies' => $data['cookies'],
                    'role' => implode(",", $user_data['role']),
                    'password' => $data['password'],
                ]);
            } else {
                // Update user data
                $user->update([
                    'organisation' => $organisation_name,
                    'email' => $user_data['email'],
                    'name' => $user_data['firstName'] . " " . $user_data['lastName'],
                    'token' => $token,
                    'role' => implode(",", $user_data['role']),
                    'cookies' => $data['cookies'],
                ]);
            }

            $response = [
                "status" => 200,
                "data" => [
                    "user" => $user,
                    "token" => $token,
                    "cookies" => $data['cookies'],
                ],
            ];
        } catch (Exception $ex) {
            $response = ["status" => 400, "error" => $ex->getMessage()];
        }
        return $response;
    }
}