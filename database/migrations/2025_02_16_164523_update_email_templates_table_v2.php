<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            // Supprimer la colonne content
            if (Schema::hasColumn('email_templates', 'content')) {
                $table->dropColumn('content');
            }

            // Ajouter les KPI (true/false, default false, nullable)
            $table->boolean('total_revenue')->default(false)->nullable();
            $table->boolean('total_orders')->default(false)->nullable();
            $table->boolean('total_employees')->default(false)->nullable();

            // Ajouter la section top_selling_items
            $table->json('top_selling_items')->nullable();

            // Ajouter les boutons
            $table->string('btn_name')->nullable();
            $table->string('btn_link')->nullable();
            $table->boolean('has_btn')->default(false);
        });
    }

    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            // Restaurer content si nécessaire
            $table->text('content')->nullable();

            // Supprimer les nouvelles colonnes
            $table->dropColumn([
                'total_revenue', 'total_orders', 'total_employees',
                'top_selling_items', 'btn_name', 'btn_link', 'has_btn'
            ]);
        });
    }
};