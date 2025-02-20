<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'subject',
        'created_at',
        'updated_at',
        'total_revenue',
        'total_orders',
        'total_employees',
        'top_selling_items',
        'btn_name',
        'btn_link',
        'has_btn',
        'template_type', // Si vous utilisez ce champ
        'is_active', // Si vous utilisez ce champ
    ];

    // Vous pouvez également ajouter des méthodes pour gérer les relations, si nécessaire
}