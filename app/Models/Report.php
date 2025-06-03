<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
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
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
