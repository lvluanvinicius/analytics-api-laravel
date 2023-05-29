<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GponOnus;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GponOnusController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $onus = GponOnus::where('collection_date', '<', '2023-04-29 20:00:07')
            ->where('collection_date', '<', '2023-04-29 20:00:07')
            ->get(['rx', 'tx', 'collection_date']);

        return $this->success($onus);
    }

    /**
     * Recupera nomes de onus em coletas.
     *
     * @param Request $request
     * @return string
     */
    public function names(Request $request)
    {
        // Verificando se o parâmetro equipament foi informado.
        if (!$request->has('equipament')) {
            return $this->error("O parâmetro 'equipament' deve ser informado.");
        }

        // Verificando se o parâmetro port foi informado.
        if (!$request->has('port')) {
            return $this->error("O parâmetro 'port' deve ser informado.");
        }

        // Carregar parametros.
        $params = $request->query();

        // Carregando times padrão para não pesar a consulta.
        $time_from = date('Y-m-d H:i', strtotime('-3 day'));;
        $time_till = date('Y-m-d H:i');
        // Recuperando parametros obrigatórios.
        $equipament = $params['equipament'];
        $port = $params['port'];
        // Realizando consulta e recuperando nomes.
        $onus = GponOnus::where('device', $equipament)
            ->where('port', $port)
            ->where('collection_date', '>=', $time_from)
            ->where('collection_date', '<=', $time_till)
            ->orderBy('name', 'asc')
            ->distinct(['name'])->pluck('name');

        return $this->success($onus);
    }

    /**
     * Recupera valores de consultas de ONUs.
     *
     * @param Request $request
     * @return json
     */
    public function onusDatasPerPeriod(Request $request)
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

        // Recuperando timerange.
        // $timeFromString = DateTime::createFromFormat('Y-m-d H:i:s', str_replace('_', ':', $params['timeFrom']));
        // $timeToString = DateTime::createFromFormat('Y-m-d H:i:s', str_replace('_', ':', $params['timeTo']));

        $timeFromString = str_replace('_', ':', $params['timeFrom']);
        $timeToString = str_replace('_', ':', $params['timeTo']);

        // Convertendo para timestamp.
        // $timestampFrom = strtotime($timeFromString);
        // $timestampTo = strtotime($timeToString);

        // .
        $equipament = $params["equipament"];
        $port = $params["port"];
        $name = $params['name'];

        // Validar parametros.

        // Validar tempo encaminhado.

        // Consulta
        $onus = new GponOnus();

        $onusData = $onus->where('device', '=', $equipament)
            ->where('port', '=', $port)
            ->where('name', '=', $name)
            ->where('collection_date', '>=', $timeFromString)
            ->where('collection_date', '<=', $timeToString)
            ->get();


        return $this->success($onusData);
    }
}

// $.data.*.m_rx - RX DBM
// $.data.*.m_tx - TX DBM
// $.data.*.collection_date - Data de Coleta
