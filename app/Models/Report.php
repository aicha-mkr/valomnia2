<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  use HasFactory;

  protected $table = 'reports';

  protected $fillable = [
    'user_id',
    'date',
    'total_orders',
    'total_revenue',
    'average_sales',
    'total_quantities',
    'total_clients',

  ];
  protected $casts = [
    'startDate' => 'date',
    'endDate' => 'date',
  ];

  /**
   * Get the user that owns the report.
   */
  public function type()
  {
    return $this->belongsTo(ReportType::class);
  }
}
