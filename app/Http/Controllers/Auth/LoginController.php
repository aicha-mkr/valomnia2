<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Session;
class LoginController extends Controller
{
    public function login(Request $request)
    {
        try{

            $credentials = $request->all();
            $data=$request->all();
           // echo json_decode($data);die();
            $response_found= User::FindUser($request->all());
            $user=$response_found['data']["user"] ?? null;
            if ($response_found["status"] == 200 && isset($user) && isset($user->token)) {
                //checkpassword
                Session::put('token_user',  $response_found['data']["token"] );
                Session::put('user', $response_found['data']["user"]);
                $roles =explode(",", $user->role);
               // echo json_encode($roles);die();
                if (array_search("SUPER_ADMIN", $roles) !== false) {
                    //echo "is heeere";die();
                    Session::put('isSuperAdmin', true);
                    Session::forget('isOrganisation');
                    return redirect()->route('dashboard-admin');
                }else{

                    Session::put('isOrganisation', true);
                    Session::forget('isSuperAdmin');
                    return redirect()->route('dashboard-organisation');
                }
            }else{
                $response = User::UserLogin($credentials);
                if ($response["status"] == 200) {
                    $user_data = $response["data"];
                    $cookies = $response["cookies"] ?? '';
                    $user_data["cookies"] = $cookies;
                    $user_data["organisation"] = $credentials["organisation"];
                    $user_data["password"] = $credentials["password"];
                    $roles = $user_data["user"]["role"];
                    // echo json_encode(array_search("ROLE_ADMIN", $roles));die();
                    //indexOf role ROLE_ADMIN
                    //if (array_search("ROLE_ADMIN", $roles) !== false) {
                        $response_created=User::UpdateOrCreated($user_data);

                        if ($response_created["status"] == 200) {
                            $user=$response_created["user"];
                            $roles =explode(",", $user->role);
                            if (array_search("SUPER_ADMIN", $roles) !== false) {
                                Session::put('isSuperAdmin', true);
                                Session::forget('isOrganisation');
                                return redirect()->route('dashboard-admin');
                            }else{
                                Session::put('isOrganisation', true);
                                Session::forget('isSuperAdmin');
                                return redirect()->route('dashboard-organisation');
                            }
                        } else {
                            return redirect()->route('auth-login')->with('error',$response_created["error"]);
                        }


/*                    } else {
                        return redirect()->route('auth-login')->with('error','Not authorized !');
                    }*/

                } else {
                    return redirect()->route('auth-login')->with('error', 'Invalid credentials');
                }
            }
        }catch (Exception $ex){
            return redirect()->route('auth-login')->with('error', $ex->getMessage());
        }
    }

    public function index()
    {
        if(session()->has('user') && session()->has('token_user')){
            if(session()->has('isOrganisation')){
                return redirect()->route('dashboard-organisation');
            }else{
                return redirect()->route('dashboard-admin');
            }
        }
        return view('content.authentications.login');
    }
    public function logout(){
        Session::forget('isSuperAdmin');
        Session::forget('token_user');
        Session::forget('user');
        Session::forget('isOrganisation');
        return redirect()->route('auth-login');
    }
}