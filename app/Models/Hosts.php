<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hosts extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_host";

    public $table = "hosts";

    protected $fillable = [
        "name"
    ];

    /**
     * Realiza a inclusão de hosts.
     *
     * @param array $dataHost
     * @return array<Hosts>
     */
    public function register(array $dataHost)
    {
        return $this->create($dataHost);
    }
}
