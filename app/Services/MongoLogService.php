<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MongoLogService
{
    public function recordLog(string $action, int $userId, array $metadata = [], ?int $characterId = null, ?int $itemId = null)
    {
        // Inserta directamente en la colección 'logs' de la conexión 'mongodb' [cite: 73]
        DB::connection('mongodb')->collection('logs')->insert([
            'action' => $action,             // Ej: 'character_created' [cite: 75, 186]
            'user_id' => $userId,            // Quien hizo la acción [cite: 76]
            'character_id' => $characterId,  // Opcional [cite: 77]
            'item_id' => $itemId,            // Opcional [cite: 78]
            'metadata' => $metadata,         // Datos extra en formato objeto/array [cite: 79]
            'created_at' => now()->toIso8601String(), // Marca de tiempo [cite: 80]
        ]);
    }
}