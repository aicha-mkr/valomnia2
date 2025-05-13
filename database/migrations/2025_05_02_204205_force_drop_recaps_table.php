<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Désactive les contraintes de clés étrangères
    Schema::disableForeignKeyConstraints();

    // Supprime la table recaps
    Schema::dropIfExists('recaps');

    // Réactive les contraintes
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Tu peux recréer la table ici si tu veux (optionnel)
  }
};
