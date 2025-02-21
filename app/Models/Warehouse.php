<?php

namespace App\Models;


use App\Models\ApiCall;
class Warehouse
{

    public static function ListStockWarhouse($data){
        $api_call=new ApiCall(true,$data["user_id"]);
        $url_api=str_replace("organisation",$data["organisation"],env('URL_API','https://organisation.valomnia.com'));
        //echo $url_api.'/api/v2.1/warehouseStocks';die();
        //echo json_encode($data);die();
        $api_response =$api_call->GetResponse( $url_api.'/api/v2.1/warehouseStocks','GET',array(),false,"JSESSIONID=".$data["cookies"]) ;
        return $api_response;

    }
      public static function ListWarhouses($data){
            $api_call=new ApiCall(true,$data["user_id"]);
            $url_api=str_replace("organisation",$data["organisation"],env('URL_API','https://organisation.valomnia.com'));
            //echo $url_api.'/api/v2.1/warehouseStocks';die();
            //echo json_encode($data);die();
            $api_response =json_decode($api_call->GetResponse( $url_api.'/api/v2.1/warehouses','GET',array(),false,"JSESSIONID=".$data["cookies"]) ,TRUE);

            if(isset($api_response["error"]) && $api_response["error"]=="no_credentials"){
                $refresh_token_response=User::RefreshAcessToken($data["user_id"]);
                if(isset($refresh_token_response["status"]) && $refresh_token_response["status"]==200 && isset($refresh_token_response["data"]["cookies"])){
                    $cookies=$refresh_token_response["data"]["cookies"] ?? '';
                    $api_response =json_decode($api_call->GetResponse( $url_api.'/api/v2.1/warehouses','GET',array(),false,"JSESSIONID=".$cookies) ,TRUE);
                }
               // echo json_encode($refresh_token_response);die();
            }
            return $api_response;


        }
}