<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'character_id',
        'item_id',
        'type',
        'executed_at'
    ];

    /**
     * Get the character that owns the inventory movement.
     */
    public function character()
    {
        /** Relationship with character */
        return $this->belongsTo(Character::class);
    }

    /**
     * Get the item associated with the movement.
     */
    public function item()
    {
        /** Relationship with item */
        return $this->belongsTo(Item::class);
    }
}