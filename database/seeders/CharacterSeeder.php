<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Character;
use App\Services\MongoLogService;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(MongoLogService $logService): void
    {
        /** Retrieve all users with the player role */
        $players = User::where('role', 'player')->get();

        /** Check if there are any players available to assign characters */
        if ($players->isEmpty()) {
            $this->command->warn('¡No hay jugadores! Asegúrate de que tu compañero haya hecho el UserSeeder.');
            return;
        }

        /** Create 2 characters for each player and record the creation in logs */
        foreach ($players as $player) {
            $characters = Character::factory()->count(2)->create([
                'user_id' => $player->id,
            ]);

            /** Generate the required MongoDB logs for each character created */
            foreach ($characters as $char) {
                $logService->recordLog(
                    action: 'character_created',
                    userId: $player->id,
                    metadata: ['name' => $char->name, 'level' => $char->level],
                    characterId: $char->id
                );
            }
        }
    }
}