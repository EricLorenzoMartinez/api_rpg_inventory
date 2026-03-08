<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a list of all items.
     */
    public function index(Request $request)
    {
        $query = Item::query();

        // Apply filters based on query parameters
        if ($request->has('type')) {
            $query->byType($request->input('type'));
        }

        // Filter equippable items if the parameter is set
        if ($request->boolean('equippable')) {
            $query->equippable();
        }

        return response()->json($query->get());
    }

    public function create()
    {
        //
    }

    /**
     * Store a new item in the database.
     */
    public function store(StoreItemRequest $request)
    {
        // Authorize the creation of a new item
        Gate::authorize('create', Item::class);

        // Create the item using validated request data
        $item = Item::create($request->validated());

        // Return the created item with a 201 status code
        return response()->json($item, 201);
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item)
    {
        // Return the details of a single item
        return response()->json($item);
    }

    /**
     * Update the specified item in the database.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        // Authorize the update of the specified item
        Gate::authorize('update', $item);

        // Update the item with the validated data
        $item->update($request->validated());

        // Return the updated item in the response
        return response()->json($item);
    }

    /**
     * Remove the specified item from the database.
     */
    public function destroy(Item $item)
    {
        // Authorize the deletion of the specified item
        Gate::authorize('delete', $item);

        // Delete the item from the database
        $item->delete();

        // Return a 204 No Content response
        return response()->json(null, 204);
    }
}
