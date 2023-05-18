<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\Zabbix\Models\Hosts;
use Illuminate\Http\Request;

class InterconnectionController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        // Verificando se o token foi informado corretamente.
        if (!$request->headers->has('zabbixtoken')) {
            return $this->error("Por favor, informe o token do zabbix no header com a chave 'zabbixtoken'.");
        }

        // Verificando se o endereço do zabbix foi informado.
        if (!$request->has('zabbixlocation')) {
            return $this->error("Por favor, o parâmetro 'zabbixlocation' é obrigatório.");
        }

        // Verificando se o groupids foi informado.
        if (!$request->has('groupids')) {
            return $this->error("Por favor, o parâmetro 'groupids' é obrigatório.");
        }

        // Recuperando token do zabbix.
        $zabbixToken = $request->headers->get('zabbixtoken');

        // Recuperando local|endereço do zabbix.
        $baseUrl = $request->query('zabbixlocation');

        //
        $groupId = $request->get('groupids');

        // Criando conexão com a API.
        $zabbix = new Hosts(
            $urlbase = $baseUrl,
            $token = $zabbixToken
        );

        $zabbixData = $zabbix->request([
            "jsonrpc" => "2.0",
            "method" => "host.get",
            "id" => 1,
            "params" => [
                "output" => ["host", "name"],
                "groupids" => explode(',', $groupId),
                "selectItems" => ["lastvalue", "name"],
                "selectInventory" => ["location_lat", "location_lon", "notes", "location", "contact"],
                "limit" => 0,
                "offset" => 0
            ]
        ]);

        // Novo array para retorno com os dados separados.
        $newHosts = [];
        foreach ($zabbixData['result'] as $zbxData) {
            array_push($newHosts, [
                "name" => $zbxData['name'],
                "host" => $zbxData['host'],
                "location" => $zbxData['inventory'],
                "lastvalue" => $zbxData["items"]
            ]);
        }

        return $this->success($newHosts, 'Hosts recuperados com sucesso.');
    }
}
