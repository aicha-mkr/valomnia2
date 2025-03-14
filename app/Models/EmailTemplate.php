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
    ];
    public function getFormattedContent(array $data)
    {
        $content = $this->content; // Assurez-vous que la colonne "content" contient le HTML

        // Remplace les placeholders par les données dynamiques
        foreach ($data as $key => $value) {
            $placeholder = "{{" . $key . "}}";
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }
}