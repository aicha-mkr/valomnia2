<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    // Specify the table if it's not the plural of the model name
    protected $table = 'email_templates';

    // Define fillable fields for mass assignment
    protected $fillable = ['name', 'subject', 'user_id'];
    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}