<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Character;
use App\Services\MongoLogService;

class CharacterSeeder extends Seeder
{
    // Inyectamos el servicio de Mongo igual que en el controlador
    public function run(MongoLogService $logService): void
    {
        // Buscamos a los jugadores (creados por el seeder de tu compañero)
        $players = User::where('role', 'player')->get();

        if ($players->isEmpty()) {
            $this->command->warn('¡No hay jugadores! Asegúrate de que tu compañero haya hecho el UserSeeder.');
            return;
        }

        // Creamos 2 personajes por jugador (mínimo 6 en total)
        foreach ($players as $player) {
            $characters = Character::factory()->count(2)->create([
                'user_id' => $player->id,
            ]);

            // Generamos los logs obligatorios en Mongo
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