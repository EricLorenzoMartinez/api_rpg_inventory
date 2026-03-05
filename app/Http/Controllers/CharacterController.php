<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Http\Requests\StoreCharacterRequest;
use App\Http\Requests\UpdateCharacterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CharacterController extends Controller
{
    //--------------------
    // LIST USER CHARACTERS
    //--------------------
    public function index(Request $request)
    {
        // Return a JSON response with only the authenticated user's characters
        return response()->json($request->user()->characters);
    }

    //--------------------
    // STORE A NEW CHARACTER
    //--------------------
    public function store(StoreCharacterRequest $request)
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

    //--------------------
    // SHOW A SPECIFIC CHARACTER
    //--------------------
    public function show(Character $character)
    {
        // Check if the user is authorized to view this character
        Gate::authorize('view', $character);

        // Return the character details
        return response()->json($character);
    }

    //--------------------
    // UPDATE A SPECIFIC CHARACTER
    //--------------------
    public function update(UpdateCharacterRequest $request, Character $character)
    {
        // Check if the user is authorized to update this character
        Gate::authorize('update', $character);

        // Update the character with the validated data
        $character->update($request->validated());

        // Return the updated character in the response
        return response()->json($character);
    }

    //--------------------
    // DELETE A SPECIFIC CHARACTER
    //--------------------
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