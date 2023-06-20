<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GponPorts extends Model
{
    use HasFactory;

    /**
     * Define a tabela relacionada ao modelo. 
     *
     * @var string
     */
    public $table = "gpon_ports";

    /**
     * Define as colunas que podem receber inserção em massa.
     *
     * @var array
     */
    public $fillable = [
        "port", "equipament_id"
    ];
    
}
