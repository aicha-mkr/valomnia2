<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $table = 'email_templates'; // Ensure this matches your table name
    protected $fillable = ['user_id', 'name', 'subject']; // Define fillable fields

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}