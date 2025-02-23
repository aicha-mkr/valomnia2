<?php

use App\Http\Controllers\AlertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\StaticDataController;
use App\Http\Controllers\TypeAlertController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\HistoriqueAlertController;

use App\Models\Alert;

Route::get("/test-alert-stock",function (){
    $alert= \App\Models\Alert::with(["user","type"])->where("id",12)->first();
    if(isset($alert)){
        $warhouses_user=App\Models\Warehouse::ListStockWarhouse(array("user_id"=>$alert->user_id,"organisation"=>$alert->user->organisation,"cookies"=>$alert->user->cookies));
        echo "warhouses_user response\n"; ;
        echo $warhouses_user;
        echo "\n"; ;

    }
})->name('test-alert');

    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::apiResource('/alerts',AlertController::class);
    Route::apiResource('/warhouse',WarehouseController::class);
    Route::apiResource('/typealerts', TypeAlertController::class);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/static-data', StaticDataController::class);
    Route::apiResource('/historiquealerts',HistoriqueAlertController::class);
    Route::get('search-users-by-id', [UserController::class, 'searchUsersById']);


    //Route::get('/static-data', StaticDataController::class);
    //Route::get('/static-data/{type}', StaticDataController::class);
   // Route::get('/static-data/{type}/date', StaticDataController::class);