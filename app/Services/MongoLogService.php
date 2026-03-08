<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MongoLogService
{
    /**
     * Record an activity log in the MongoDB database.
     */
    public function recordLog(
        string $action,
        int $userId,
        array $metadata = [],
        ?int $characterId = null,
        ?int $itemId = null
    ) {
        // Use the MongoDB connection to insert a new log entry into the logs table
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
