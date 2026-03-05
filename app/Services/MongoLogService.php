<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MongoLogService
{
    public function recordLog(string $action, int $userId, array $metadata = [], ?int $characterId = null, ?int $itemId = null)
    {
        // Usamos table() en lugar de collection() para la versión moderna del driver
        DB::connection('mongodb')->table('logs')->insert([
            'action' => $action,
            'user_id' => $userId,
            'character_id' => $characterId,
            'item_id' => $itemId,
            'metadata' => $metadata,
            'created_at' => now()->toIso8601String(),
        ]);
    }
}
