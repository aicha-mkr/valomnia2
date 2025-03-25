<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameKpiColumnsInEmailTemplatesTable extends Migration
{
    public function up(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->renameColumn('total_revenue', 'revenue_generated');
            $table->renameColumn('total_orders', 'number_of_orders');
            $table->renameColumn('total_employees', 'average_basket_size');
        });
    }

    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->renameColumn('revenue_generated', 'total_revenue');
            $table->renameColumn('number_of_orders', 'total_orders');
            $table->renameColumn('average_basket_size', 'total_employees');
        });
    }
}