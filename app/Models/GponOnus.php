<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class GponOnus extends Model
{
    use HasFactory;

    /**
     * Define a tabela para model.
     *
     * @var string
     */
    public $table = "gpon_onus";

    /**
     * Propriedades liberadas a inserção em massa.
     *
     * @var array
     */
    protected $fillable = [
        "name", "serial_number", "device", "pon", "onuid", "rx", "tx", "collection_date"
    ];

    /**
     * Desativa a inserção de dados em created_at e updated_at.
     * Desativa o uso do timestamp.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Recupera valores agregados por dia.
     *
     * @param array $params
     * @return array
     */
    public function onusDatasAggregateDays(array $params)
    {
        // $key = "collection_model_onus_datas_three_hours_" . $params['name']; // Chace de cache.
        // $expSecons = 3600; // Seta 1H em cache.
        // // Realiza a consulta novamente se não houver cache.
        // $onus = Cache::remember($key, $expSecons, function () use ($params) {
        //     return GponOnus::select([
        //         DB::raw('DATE(collection_date) as collection_date'),
        //         DB::raw('ROUNd(AVG(TX)::numeric, 2) as m_tx'),
        //         DB::raw('ROUNd(AVG(RX)::numeric, 2) as m_rx'),
        //     ])
        //         ->where('name', $params['name'])
        //         ->where('device', $params['equipament'])
        //         ->where('port', $params['port'])
        //         ->where('collection_date', '>=', $params['time_from'])
        //         ->where('collection_date', '<=', $params['time_till'])
        //         ->groupBy('collection_date')
        //         ->get();
        // });

        return GponOnus::select([
            DB::raw('TX as m_tx'),
            DB::raw('RX as m_rx'),
            DB::raw('collection_date'),
        ])
            ->where('name', $params['name'])
            ->where('device', $params['equipament'])
            ->where('port', $params['port'])
            ->orderBy('collection_date', 'asc')
            ->where('collection_date', '>=', $params['time_from'])
            ->where('collection_date', '<=', $params['time_till'])
            ->get();

        // $onus = Cache::remember($key, $expSecons, function () use ($params) {
        // });


    }


    /**
     * Recupera valores agregados a cada 3 horas.
     *
     * @param array $params
     * @return array
     */
    public function onusDatasAggregateThreeHours($params)
    {
        // $key = "collection_model_onus_datas_three_hours_" . $params['name']; // Chace de cache.
        // $expSecons = 3600; // Seta 1H em cache.
        // // Realiza a consulta novamente se não houver cache.
        // $onus =  Cache::remember($key, $expSecons, function () use ($params) {

        //     return GponOnus::select([
        //         DB::raw("DATE_TRUNC('hour', collection_date) + INTERVAL '3 hour' * (EXTRACT(hour FROM collection_date)::integer / 3 * 3) as collection_date"),
        //         DB::raw('ROUND(AVG(TX)::numeric,2) as m_tx'),
        //         DB::raw('ROUND(AVG(RX)::numeric,2) as m_rx'),
        //     ])
        //         ->where('name', $params['name'])
        //         ->where('device', $params['equipament'])
        //         ->where('port', $params['port'])
        //         ->where('collection_date', '>=', $params['time_from'])
        //         ->where('collection_date', '<=', $params['time_till'])
        //         ->groupBy('collection_date')
        //         ->get();
        // });

        // return $onus;

        return GponOnus::select([
            DB::raw("DATE_TRUNC('hour', collection_date) + INTERVAL '3 hour' * (EXTRACT(hour FROM collection_date)::integer / 3 * 3) as collection_date"),
            DB::raw('ROUND(AVG(TX)::numeric,2) as m_tx'),
            DB::raw('ROUND(AVG(RX)::numeric,2) as m_rx'),
        ])
            ->where('name', $params['name'])
            ->where('device', $params['equipament'])
            ->where('port', $params['port'])
            ->where('collection_date', '>=', $params['time_from'])
            ->where('collection_date', '<=', $params['time_till'])
            ->groupBy('collection_date')
            ->orderBy('collection_date', 'asc')
            ->get();
    }
}
