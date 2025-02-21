<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Alert extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * Get the user that owns the alert.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the type associated with the alert.
     */
    public function type()
    {
        return $this->belongsTo(TypeAlert::class, 'type_id', 'id');
    }

    /**
     * Get the historique alerts for the alert.
     */
    public function historiqueAlerts()
    {
        return $this->hasMany(AlertHistory::class, 'alert_id', 'id');
    }
}