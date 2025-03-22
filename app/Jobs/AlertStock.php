<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Models\Warehouse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AlertStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $alert_id;

    public function __construct($alert_id)
    {
        $this->alert_id = $alert_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $alert=Alert::with(["user","type"])->where("id",$this->alert_id)->first();
        echo "alert Id : ".$alert->id."\n"; ;
        echo "user_id : ".$alert->user_id."\n"; ;
        echo "organisation : ".$alert->user->organisation."\n"; ;
        if(isset($alert)){
            // setting_template
            $warhouses_user=Warehouse::ListStockWarhouse(array("user_id"=>$alert->user_id,"organisation"=>$alert->user->organisation,"warhouseRef"=>$alert->warehouse_ids));
            $warhouses_user_ar=json_decode($warhouses_user,TRUE);
            if(isset($warhouses_user_ar["data"])){
                foreach ($warhouses_user_ar["data"] as $warhouse){
                    if(isset($warhouse["quantity"]) && $warhouse["quantity"]<= $alert->quantity){
                      // send email
                    }
                }
            }
            echo "warhouses_user response\n"; ;
            echo $warhouses_user;
            echo "\n"; ;
            //send email

        }
    }
}
