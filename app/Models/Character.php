<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'level',
        'user_id'
    ];

    /**
     * Get the user that owns the character.
     */
    public function user()
    {
        /** Mandatory relationship with users */
        return $this->belongsTo(User::class);
    }

    /**
     * Get the inventory movements for the character.
     */
    public function inventoryMovements()
    {
        /** Relationship with inventory movements */
        return $this->hasMany(InventoryMovement::class);
    }
}