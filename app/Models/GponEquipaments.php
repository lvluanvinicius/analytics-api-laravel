<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GponEquipaments extends Model
{
    use HasFactory;

    public $table = "gpon_equipaments";

    public $fillable = [
        "name", "n_port"
    ];
}
