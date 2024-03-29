<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GponOnus;
use App\Traits\ApiResponser;
use DateTime;
use Illuminate\Http\Request;

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

        $timeFromString = str_replace('%', ' ', str_replace('_', ':', $params['timeFrom']));
        $timeToString = str_replace('%', ' ', str_replace('_', ':', $params['timeTo']));

        // Convertendo para timestamp.
        $timestampFrom = DateTime::createFromFormat('Y-m-d H:i:s', $timeFromString);
        $timestampTo = DateTime::createFromFormat('Y-m-d H:i:s', $timeToString);

        if (!$timestampFrom || !$timestampTo) {
            return $this->error("Data deve ser informada no formato 'Y-m-d H:i:s'");
        }

        $equipament = $params["equipament"];
        $port = $params["port"];
        $name = $params['name'];

        // Consulta
        $onus = new GponOnus();

        $onusData = $onus->where('device', '=', $equipament)
            ->where('port', '=', $port)
            ->where('name', '=', $name)
            ->where('collection_date', '>=', $timestampFrom->format('Y-m-d H:i:s'))
            ->where('collection_date', '<=', $timestampTo->format('Y-m-d H:i:s'))
            ->get();


        return $this->success($onusData);
    }
}