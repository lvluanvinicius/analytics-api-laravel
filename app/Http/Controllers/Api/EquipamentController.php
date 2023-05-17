<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipamentsRequest;
use App\Models\GponEquipaments;
use App\Models\GponPorts;
use App\Rules\EquipamentsRules;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EquipamentController extends Controller
{
    use ApiResponser;

    /**
     * Retorna todos os equipamentos.
     *
     * @return string
     */
    public function index()
    {
        $equipaments = GponEquipaments::get("name");

        return response()->json($equipaments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipamentsRequest $request)
    {

        $equipamentValidate = new EquipamentsRules();

        // Validando dados.
        $errorValidate = $equipamentValidate->validate($request->name);
        if (!$errorValidate['status']) {
            return $this->error($errorValidate['message']);
        }

        try {
            // Criando equipamentos.
            $gponEquipament = new GponEquipaments();

            $gponEquipament->name = $request->name;
            $gponEquipament->n_port = $request->number_ports;

            // // Salvando e validando se ouve registro.
            if ($gponEquipament->save()) {

                // Gerando strings de identificação de portas no padrão Datacom.
                $equipament = [];
                for ($p = 1; $p < $request->number_ports + 1; $p++) {
                    // salvando no auxiliar os dados gerados a partir da quantidade de portas informada no request..
                    array_push($equipament, ["port" => "gpon 1/1/$p", "equipament_id" => $gponEquipament->id]);
                }

                // Realizando Insert em Massa de todas as portas gerdas.
                $gponPorts = GponPorts::insert($equipament);

                // Revert a inserção do equipamento se as lportas não forem salvas.
                if (!$gponPorts) {
                    $gponEquipament->destroy($gponEquipament->id);
                    return $this->error("Erro ao tentar criar as portas para o equipameto $request->name.");
                }

                return $this->success('Equipamento criado com sucesso.');
            }
        } catch (Exception | ModelNotFoundException $error) {
            return $this->error($error->getMessage());
        }


        return $this->error("Não foi possível criar o equipamento.");
    }
}
