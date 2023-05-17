<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GponPorts;
use App\Models\GponEquipaments;
use App\Traits\ApiResponser;

class PortsController extends Controller
{
    use ApiResponser;

    /**
     * Recupera todas portas com relação.
     *
     * @return string
     */
    public function index(string $equipament_name)
    {
        // Recupera o id de equipamento.
        $equipament = GponEquipaments::where('name', $equipament_name)->first('id');
        // Recupera todas as portas relacionada ao equipamento encaminhado.
        $ports = GponPorts::where('equipament_id', $equipament->id)->get('port');

        return $this->success($ports);
    }
}
