<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'subject',
        'title',
        'content',
        'created_at',
        'updated_at',
        'total_revenue',
        'total_orders',
        'total_employees',
        'top_selling_items',
        'btn_name',
        'btn_link',
        'has_btn',
        'is_active',
        'alert_id', 
        'alert_title',
        'average_sales',
        'total_quantities',
        'total_clients',
    ];

    protected $casts = [
        'total_orders' => 'boolean',
        'total_revenue' => 'boolean',
        'average_sales' => 'boolean',
        'total_quantities' => 'boolean',
        'total_clients' => 'boolean',
        'top_selling_items' => 'boolean',
    ];

    public function getFormattedContent(array $data)
    {
        $content = $this->content; 
        foreach ($data as $key => $value) {
            $placeholder = "{{" . $key . "}}";
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }
}