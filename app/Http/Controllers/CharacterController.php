<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Http\Requests\StoreCharacterRequest;
use App\Http\Requests\UpdateCharacterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        // Lista solo los personajes del usuario
        return response()->json($request->user()->characters);
    }

    public function store(StoreCharacterRequest $request)
    {
        // Se crea el personaje asignándole el user_id del usuario logueado automáticamente
        $character = $request->user()->characters()->create($request->validated());

        $logService->recordLog(
            action: 'character_created',
            userId: $request->user()->id,
            metadata: [
                'name' => $character->name,
                'level' => $character->level
            ],
            characterId: $character->id
        );

        return response()->json($character, 201);
    }

    public function show(Character $character)
    {
        Gate::authorize('view', $character);
        return response()->json($character);
    }

    public function update(UpdateCharacterRequest $request, Character $character)
    {
        Gate::authorize('update', $character);
        $character->update($request->validated());
        return response()->json($character);
    }

    public function destroy(Character $character)
    {
        Gate::authorize('delete', $character);
        $character->delete();
        return response()->json(null, 204);
    }
}
