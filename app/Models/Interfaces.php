<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interfaces extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_host_fk";

    public $table = "interfaces";

    protected $fillable = [
        'name', 'address', 'port', 'cod_host_fk',
    ];

    public function register(array $interfaces)
    {
        return $this->create($interfaces);
    }
}
