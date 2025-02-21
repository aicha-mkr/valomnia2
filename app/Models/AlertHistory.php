<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertHistory extends Model
{
    use HasFactory;

    protected $table = 'alerts_history';
    //status enum
    ///*{0:pedding,1:encours,2:failed,3:completed}
    ///
    ///  */
    protected $guarded = [];

    /**
         * Get the alert that owns the historique alert.
         */
        public function alert()
        {
            return $this->belongsTo(Alert::class, 'alert_id', 'id');
        }

        /**
         * Get the user that owns the historique alert.
         */
        public function user()
        {
            return $this->belongsTo(User::class, 'iduser', 'id');
        }
}
