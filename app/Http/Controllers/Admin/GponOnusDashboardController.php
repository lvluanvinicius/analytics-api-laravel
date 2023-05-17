<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GponEquipaments;
use App\Models\GponOnus;
use App\Models\GponPorts;
use App\Traits\ApiResponser;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GponOnusDashboardController extends Controller
{
    use ApiResponser;

    /**
     * Retorna display do dashboard de Onus.
     *
     * @return View
     */
    public function index()
    {
        $equipaments = GponEquipaments::orderBy('name', 'asc')->get('name');

        return view('tests.index')->with([
            'title' => 'Painel Onus',
            'equipaments' => $equipaments
        ]);
    }

    /**
     * Retorna todas as portas cadastradas de um equipamento.
     *
     * @param string $equipament
     * @return string
     */
    public function ports(string $equipament)
    {
        // Recuperando equipamento em acordo com o name encaminhado.
        // Name seria um valor unico na base.
        $gponEquipament = GponEquipaments::where('name', $equipament)->first('id');

        // Recuperando portas relacionadas ao equipamento encaminhado.
        $ports = GponPorts::where('equipament_id', $gponEquipament->id)->get(["port"]);

        return $this->success($ports);
    }

    /**
     * Recupera todos os nomes de Onus em uma determinada data de um dia atrás.
     *
     * @param Request $request
     * @return string
     */
    public function names(Request $request)
    {
        // Carregar parametros.
        $params = $request->query();

        $time_from = date('Y-m-d H:i', strtotime('-1 day'));;
        $time_till = date('Y-m-d H:i');
        $equipament = $params['equipament'];
        $port = $params['port'];

        $names = Cache::remember("equipament_port_names_$port-$equipament", 3600, function () use (
            $time_from,
            $time_till,
            $equipament,
            $port
        ) {
            return GponOnus::where('device', $equipament)
                ->where('port', $port)
                ->where('collection_date', '>=', $time_from)
                ->where('collection_date', '<=', $time_till)
                ->orderBy('name', 'asc')
                ->distinct(['name'])->pluck('name');
        });

        return $this->success($names);
    }

    /**
     * Carregamento de dados de ONUs.
     *
     * @param Request $request
     * @return string
     */
    public function datasOnus(Request $request)
    {
        // Carregar parametros.
        $params = $request->query();

        // Recuperando parametros.
        $time_from = $params['time_from'];
        $time_till = $params['time_till'];
        $equipament = $params['equipament'];
        $port = $params['port'];
        $name = $params['onu_name'];

        // Defina as datas de início e fim
        $dateStart = new DateTime($time_from);
        $dateEnd = new DateTime($time_till);

        // Calcule a diferença entre as datas
        $diff = $dateStart->diff($dateEnd);

        // Obtenha a diferença em dias
        $days = $diff->days;

        // Modelo de Onus.
        $gponOnus = new GponOnus();

        // Construindo novo array de parâmetros já com dados formatados.
        $newParams = [
            'time_from' =>  $time_from,
            'time_till' =>  $time_till,
            'equipament' =>  $equipament,
            'port' => $port,
            'name' => $name,
        ];

        $onus = [];

        // Verificando tempo solicitado de consulta.
        if ($days <= 3) {
            // Retorna valores agregados a cada 3 horas.
            $onus = $gponOnus->onusDatasAggregateThreeHours($newParams);
        } else {
            // Retorna valores agregados por dia.
            $onus = $gponOnus->onusDatasAggregateDays($newParams);
        }


        return $this->success($onus);
    }
}
