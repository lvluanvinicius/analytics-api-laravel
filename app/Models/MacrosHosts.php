<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MacrosHosts extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_macro_host";

    public $table = "macros_host";

    protected $fillable = [
        "cod_host_fk", "macro", "value", "type", "description",
    ];


    public function register(array $macros)
    {
        return $this->create($macros);
    }
}
