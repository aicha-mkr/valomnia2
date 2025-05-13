<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParametersToAlertsTable extends Migration
{
  public function up(): void
  {
    Schema::table('alerts', function (Blueprint $table) {
      // Supprimer les anciennes colonnes si elles existent
      if (Schema::hasColumn('alerts', 'warehouse_ids')) {
        $table->dropColumn('warehouse_ids');
      }


      // Ajouter une colonne JSON pour stocker les paramètres
      $table->json('parameters')->nullable()->after('description');
    });
  }

  public function down(): void
  {
    Schema::table('alerts', function (Blueprint $table) {
      // Réajouter les colonnes supprimées (si rollback)
      $table->string('warehouse_ids')->nullable();

      // Supprimer la colonne parameters
      $table->dropColumn('parameters');
    });
  }
}
