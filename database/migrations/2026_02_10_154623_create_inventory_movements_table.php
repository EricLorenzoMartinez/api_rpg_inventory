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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            
            // Relaciones obligatorias [cite: 64-66]
            $table->foreignId('character_id')->constrained('characters')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            
            // Tipo de movimiento (LOOT, EQUIP, UNEQUIP, DROP) [cite: 67]
            $table->string('type');
            
            // Fecha de ejecución, por defecto el momento actual [cite: 70]
            $table->timestamp('executed_at')->useCurrent();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};