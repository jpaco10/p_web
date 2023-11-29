<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    
    protected $table = 'vehicles';

    protected $fillable = [
        'brand',
        'model',
        'year',
        'licence_plate',
        'client_id'
    ];
}
