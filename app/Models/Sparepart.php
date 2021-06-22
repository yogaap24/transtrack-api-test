<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicles_id', 'type_sparepart', 'name_sparepart', 'condition_sparepart',
    ];
}
