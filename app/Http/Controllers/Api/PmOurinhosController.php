<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\Zabbix\Models\LocalesTriggers;
use Exception;
use Illuminate\Http\Request;

class PmOurinhosController extends Controller
{
    use ApiResponser;


    public function index(Request $request)
    {
        try {
            // Carregando localizações.
            $location_data = json_decode(file_get_contents(storage_path('app/locales/location.json')));

            // Criando modelo de conexão com Zabbix Api
            $zabbixLocales = new LocalesTriggers(
                $urlbase = $request->get('zabbixlocation'),
                $token = $request->headers->get('zabbixtoken')
            );

            // Buscando dados.
            $zabbixData = $zabbixLocales->request([
                "jsonrpc" => "2.0",
                "method" => "item.get",
                "id" => 1,
                "params" => [
                    "output" => ["triggers", "lastvalue"],
                    "filter" => [
                        "groupids" => [config('zabbix.groups.pm_our')],
                    ],
                    "search" => [
                        "key_" => "ifOnuOperStatus",
                        "name" => "PMO"
                    ],
                    "selectTags" => [
                        "tag", "value",
                    ],
                    "selectTriggers" => [
                        "triggers", "description", "comments", "status",
                    ],
                ],
            ]);

            // Novo array para retorno com os dados separados.
            $newZabbixData = [];
            foreach ($zabbixData['result'] as $zbxData) {
                $trigger_name = explode(' ', $zbxData['triggers'][0]['description'])[2];
                $status = $zbxData['lastvalue'];

                foreach ($location_data as $lc) {
                    // return $lc;
                    if (trim($trigger_name) == $lc->key) {
                        array_push($newZabbixData, [
                            "name" => $lc->name,
                            "itemid" => $zbxData['itemid'],
                            "triggers" => $zbxData['triggers'],
                            "status" =>  $status,
                            "locale" => [
                                $lc->lat,
                                $lc->lon,
                            ],
                        ]);
                    }
                }
            }

            // return $this->succes
            return $this->success($newZabbixData);
        } catch (Exception $error) {
            return $this->error($error->getMessage());
        }
    }
}
