<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\Zabbix\Models\History;
use Illuminate\Http\Request;

class PercentilController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        // From: ${__from:date:YYYY-MM-DD HH_mm_ss} ------------- To: ${__to:date:YYYY-MM-DD HH_mm_ss}

        // Verificando se o endereço do zabbix foi informado.
        if (!$request->has('timeFrom') || !$request->has('timeTo')) {
            return $this->error("Por favor, os parâmetros 'timeFrom' e 'timeTo' são obrigatórios.");
        }

        // Verificando se o token foi informado corretamente.
        if (!$request->headers->has('zabbixtoken')) {
            return $this->error("Por favor, informe o token do zabbix no header com a chave 'zabbixtoken'.");
        }

        // Verificando se o endereço do zabbix foi informado.
        if (!$request->has('zabbixlocation')) {
            return $this->error("Por favor, o parâmetro 'zabbixlocation' é obrigatório.");
        }

        // Verificando se o itemids foi informado.
        if (!$request->has('itemids')) {
            return $this->error("Por favor, o parâmetro 'itemids' é obrigatório.");
        }

        // Recuperando token de autenticação do zabbix.
        $zabbixToken = $request->headers->get('zabbixtoken');

        // Recuperando local|endereço do zabbix.
        $baseUrl = $request->query('zabbixlocation');

        // Recuperando valor de itemids.
        $itemids = $request->query('itemids');

        // Recuperando timerange.
        $timeFromString = str_replace('_', ':', $request->timeFrom);
        $timeToString = str_replace('_', ':', $request->timeTo);
        // Convertendo para timestamp.
        $timestampFrom = strtotime($timeFromString);
        $timestampTo = strtotime($timeToString);

        // Criando conexão com a API.
        $zabbix = new History(
            $urlbase = $baseUrl,
            $token = $zabbixToken
        );

        $zabbixData = $zabbix->request([
            "jsonrpc" => "2.0",
            "method" => "history.get",
            "id" => 1,
            "params" => [
                "output" => "extend",
                "history" => 3,
                "itemids" => [$itemids],
                "sortorder" => "DESC",
                "sortfield" => "clock",
                "time_from" => $timestampFrom,
                "time_till" => $timestampTo
            ]
        ]);

        // return $this->succes
        return $this->success($this->calcPercentil($zabbixData));
    }

    private function calcPercentil(array $history)
    {
        $auxItem = [];

        // Separando itens acima de 10G.
        foreach ($history['result'] as $hy) {
            if ($hy['value'] > 10000000000) {
                array_push($auxItem, $hy['value']);
            }
        }

        // Ordenando valores.
        sort($auxItem);

        $original_size = count($auxItem); // Recuperando tamanho original.
        $num_elements_remove = ceil(0.05 * $original_size); // Arredondando valor.
        $auxItem = array_slice($auxItem, $num_elements_remove); // Removendo elementos.

        // Somando os valores.
        $sumValues = array_sum(array_map('intval', $auxItem));

        // Somando convertendo em bytes.
        $value_mb = $sumValues / (8 *  1000000);

        // Cálculo de gastos.
        $real = number_format($value_mb * 2.20, 2, ',', '.');
        // $real = $value_mb * 2.20;


        return [
            "consumption" => $sumValues,
            "value" => formatSpeed($value_mb),
            "real" => $real,
        ];
    }
}
