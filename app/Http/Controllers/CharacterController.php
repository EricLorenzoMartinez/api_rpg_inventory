<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Http\Requests\StoreCharacterRequest;
use App\Http\Requests\UpdateCharacterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\MongoLogService;

class CharacterController extends Controller
{
    /**
     * List user characters.
     */
    public function index(Request $request)
    {
        $characters = Character::mine()->get();
        return response()->json($characters);
    }

    /**
     * Store a new character.
     */
    public function store(StoreCharacterRequest $request, MongoLogService $logService)
    {
        // Create the character automatically assigning the logged-in user's ID
        $character = $request->user()->characters()->create($request->validated());

        // Record the character creation in the logs
        $logService->recordLog(
            action: 'character_created',
            userId: $request->user()->id,
            metadata: [
                'name' => $character->name,
                'level' => $character->level
            ],
            characterId: $character->id
        );

        // Return the newly created character in the response
        return response()->json($character, 201);
    }

    /**
     * Show a specific character.
     */
    public function show(Character $character)
    {
        // Check if the user is authorized to view this character
        Gate::authorize('view', $character);

        // Return the character details
        return response()->json($character);
    }

    /**
     * Update a specific character.
     */
    public function update(UpdateCharacterRequest $request, Character $character)
    {
        // Check if the user is authorized to update this character
        Gate::authorize('update', $character);

        // Update the character with the validated data
        $character->update($request->validated());

        // Return the updated character in the response
        return response()->json($character);
    }

    /**
     * Delete a specific character.
     */
    public function destroy(Character $character)
    {
        // Check if the user is authorized to delete this character
        Gate::authorize('delete', $character);

        // Delete the character from the database
        $character->delete();

        // Return a 204 No Content response indicating successful deletion
        return response()->json(null, 204);
    }
}
