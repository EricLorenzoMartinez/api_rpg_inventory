<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //--------------------
    // CONNECTION PROPERTIES
    //--------------------
    
    // Specify the custom database connection for MongoDB
    protected $connection = 'mongodb';

    // Define the specific collection name within MongoDB
    protected $collection = 'logs';
}