<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistance_request extends Model
{
    use HasFactory;

    protected $table = 'assistance_requests';

    protected $fillable = [
        'client_id',
        'workshop_id',
        'vehicle_id',
        'technician_id',
        'problem_description',
        'latitud',
        'longitud',
        'photos',
        'voice_note',
        'status'
    ];
}
