<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Support\Facades\Gate;

class ItemController extends Controller
{
    /**
     * DISPLAY A LIST OF ALL ITEMS
     */
    public function index()
    {
        return response()->json(Item::all());
    }

    public function create()
    {
        //
    }

    /**
     * STORE A NEW ITEM IN THE DATABASE
     */
    public function store(StoreItemRequest $request)
    {
        Gate::authorize('create', Item::class);

        $item = Item::create($request->validated());
        return response()->json($item, 201);
    }

    /**
     * DISPLAY THE SPECIFIED ITEM
     */
    public function show(Item $item)
    {
        return response()->json($item);
    }

    /**
     * UPDATE THE SPECIFIED ITEM IN THE DATABASE
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        Gate::authorize('update', $item);

        $item->update($request->validated());
        return response()->json($item);
    }

    /**
     * REMOVE THE SPECIFIED ITEM FROM THE DATABASE
     */
    public function destroy(Item $item)
    {
        Gate::authorize('delete', $item);

        $item->delete();
        return response()->json(null, 204);
    }
}
