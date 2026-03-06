<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\InventoryMovement;
use App\Models\User;

class InventoryMovementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        /** Standard policy set to false by default */
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InventoryMovement $inventoryMovement): bool
    {
        /** Standard policy set to false by default */
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        /** Standard policy set to false by default */
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InventoryMovement $inventoryMovement): bool
    {
        /** Standard policy set to false by default */
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InventoryMovement $inventoryMovement): bool
    {
        /** Standard policy set to false by default */
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InventoryMovement $inventoryMovement): bool
    {
        /** Standard policy set to false by default */
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InventoryMovement $inventoryMovement): bool
    {
        /** Standard policy set to false by default */
        return false;
    }
}