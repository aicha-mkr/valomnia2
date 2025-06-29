<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'template_id',
    'date',
    'total_orders',
    'total_revenue',
    'average_sales',
    'total_quantities',
    'total_clients',
    'top_selling_items',
    'startDate',
    'endDate',
    'schedule',
    'users_email',
    'time',
    'status',
  ];

  protected $casts = [
    'startDate' => 'datetime',
    'endDate' => 'datetime',
    'date' => 'datetime',
    'total_revenue' => 'decimal:2',
    'average_sales' => 'decimal:2',
    'status' => 'boolean',
    'time' => 'datetime:H:i',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public static function reportValomnia($data){
    $api_call=new ApiCall(true,$data["user_id"]);
    $url_api=str_replace("organisation",$data["organisation"],env('URL_API','https://organisation.valomnia.com'));
    //echo $url_api.'/api/v2.1/warehouseStocks';die();
    //echo json_encode($data);die();
    $api_response =$api_call->GetResponse( $url_api.'/api/v2.1/report','GET',array('startDate'=>$data["startDate"],'endDate'=>$data["endDate"]),false,"JSESSIONID=".$data["cookies"]) ;
    return $api_response;

  }
}
