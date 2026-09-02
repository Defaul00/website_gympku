<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymEquipment extends Model
{
    /** @use HasFactory<\Database\Factories\GymEquipmentFactory> */
    use HasFactory;

    protected $table = 'gym_equipments';

    protected $fillable = [
        'name',
        'category',
        'condition',
        'last_maintenance',
        'next_maintenance',
    ];

    protected $casts = [
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
    ];
}
