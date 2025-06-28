<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            // Remove old KPI fields if they exist
            if (Schema::hasColumn('email_templates', 'revenue_generated')) {
                $table->dropColumn('revenue_generated');
            }
            if (Schema::hasColumn('email_templates', 'number_of_orders')) {
                $table->dropColumn('number_of_orders');
            }
            if (Schema::hasColumn('email_templates', 'average_basket_size')) {
                $table->dropColumn('average_basket_size');
            }
            if (Schema::hasColumn('email_templates', 'top_selling_items')) {
                $table->dropColumn('top_selling_items');
            }
            // Add new KPI boolean fields
            $table->boolean('total_orders')->default(false);
            $table->boolean('total_revenue')->default(false);
            $table->boolean('average_sales')->default(false);
            $table->boolean('total_quantities')->default(false);
            $table->boolean('total_clients')->default(false);
            $table->boolean('top_selling_items')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn(['total_orders', 'total_revenue', 'average_sales', 'total_quantities', 'total_clients', 'top_selling_items']);
            // Optionally, you can re-add the old fields if needed
            // $table->boolean('revenue_generated')->default(false);
            // $table->boolean('number_of_orders')->default(false);
            // $table->boolean('average_basket_size')->default(false);
            // $table->boolean('top_selling_items')->default(false);
        });
    }
};
