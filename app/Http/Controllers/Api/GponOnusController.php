<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GponOnus;
use App\Traits\ApiResponser;
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
        // Carregar parametros.
        $params = $request->query();

        $time_from = date('Y-m-d H:i', strtotime('-3 day'));;
        $time_till = date('Y-m-d H:i');
        $equipament = $params['equipament'];
        $port = $params['port'];

        $onus = GponOnus::where('device', $equipament)
            ->where('port', $port)
            ->where('collection_date', '>=', $time_from)
            ->where('collection_date', '<=', $time_till)
            ->distinct(['name'])->pluck('name');

        return $this->success($onus);
    }

    /**
     * Recupera valores de consultas de ONUs.
     *
     * @param Request $request
     * @return json
     */
    public function datasOnus(Request $request)
    {
        $onus = new GponOnus();

        $params = $request->query();

        $equipament = $params["equipament"];
        $port = $params["port"];
        // $time_from = $params["time_from"];
        // $time_till = $params["time_till"];
        $name = $params['name'];
        $timer = $params['timer'];

        // Validar parametros.
        // ...

        // Validar tempo encaminhado.

        // Consulta
        // $onusData = $onus->onusDatasAggregateDays([
        //     'equipament' => $equipament,
        //     'port' => $port,
        //     // 'time_from' => $time_from,
        //     // 'time_till' => $time_till,
        //     'name' =>  $name,
        //     'hour' => 3
        // ]);

        $valueTime = Str::replace(['m', 'h', 'd', 'y'], [' minutes', ' hours', ' days', ' years'], $timer);
        var_dump($valueTime);
        die();
        $actualDate = time(); // Obtém o timestamp da data atual
        $days = date('Y-m-d H:i:s', strtotime("-$valueTime", $actualDate));

        return $this->success($days);
    }
}
