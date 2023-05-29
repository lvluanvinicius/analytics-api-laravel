<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GponOnus;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class GponGetDatesController extends Controller
{
    use ApiResponser;

    public function getDates(Request $request)
    {
        // Verificando se o endereço do zabbix foi informado.
        if (!$request->has('timeFrom') || !$request->has('timeTo')) {
            return $this->error("Por favor, os parâmetros 'timeFrom' e 'timeTo' são obrigatórios.");
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
        $timeFromString = str_replace('_', ':', $params['timeFrom']);
        $timeToString = str_replace('_', ':', $params['timeTo']);

        $equipament = $params["equipament"];
        $port = $params["port"];

        $onus = new GponOnus();

        $onusData = $onus->where('device', '=', $equipament)
            ->where('port', '=', $port)
            ->where('collection_date', '>=', $timeFromString)
            ->where('collection_date', '<=', $timeToString)
            ->orderBy('collection_date', 'desc')
            ->select('collection_date')
            ->distinct('collection_date')
            ->get();

        return $this->success($onusData);
    }
}
