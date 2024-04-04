<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GponOnus;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class GponOnusPerPortsController extends Controller
{
    use ApiResponser;

    public function onusPerPorts(Request $request)
    {
        // Verificando se o endereço do zabbix foi informado.
        if (!$request->has('collection_date')) {
            return $this->error("Por favor, o parâmetro 'collection_date' é obrigatório.");
        }

        // Verificando se o parâmetro equipament foi informado.
        if (!$request->has('equipament')) {
            return $this->error("O parâmetro 'equipament' deve ser informado.");
        }

        // Verificando se o parâmetro port foi informado.
        if (!$request->has('port')) {
            return $this->error("O parâmetro 'port' deve ser informado.");
        }

        $params = $request->query();

        // Recuperando timerange

        $equipament = $params["equipament"];
        $port = $params["port"];
        $collection_date = $params["collection_date"];

        $onus = new GponOnus();

        $onusData = $onus->where('device', '=', $equipament)
            ->where('port', '=', $port)
            ->where('collection_date', '=', $collection_date)
            ->orderBy('name', 'asc')
            ->get(['onuid', 'serial_number', 'name', 'tx', 'rx', 'device', 'port']);

        return $this->success($onusData);
    }
}