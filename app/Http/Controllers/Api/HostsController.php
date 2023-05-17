<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hosts;
use App\Models\Interfaces;
use App\Models\MacrosHosts;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;

class HostsController extends Controller
{
    use ApiResponser;

    /**
     * Recupera todos os hosts.
     *
     * @return void
     */
    public function index()
    {
        # Instancia do modelo host.
        $hosts = new Hosts();

        return $this->success($hosts->where('status', true)->get(["cod_host", "name", "description", "status"]));
    }

    /**
     * Recupera os dados de interfaces para determinado host.
     *
     * @return array<string>
     */
    public function getInterface()
    {
        # Instancia do modelo host.
        $hosts = new Hosts();

        // Recuperando status
        $hostData = $hosts->where('cod_host', 1)->where('status', true)->first("status");

        // Verificando se encontra algum registro com o id encaminhado.
        if (!$hostData) {
            return $this->error('Host não encontrado.');
        }

        // Verificando se o host está ativo.
        if (!$hostData->status) {
            return $this->error('O host solicitado está inativo.');
        }

        // Modelo de Interfaces
        $interfaces = new Interfaces();

        // Recuperando Interface
        $interfacesData = $interfaces->where('cod_host_fk', 1)->get();

        // Verificando se houve algum registro de interface para o host solicitado.
        if (!$interfacesData) {
            return $this->success('Nenhuma interface encontrada.');
        }

        return $this->success($interfacesData, "Interfaces recuperadas com sucesso.");
    }

    /**
     * Recupera os dados de macros para um determinado host.
     *
     * @return array<string>
     */
    public function getMacros()
    {
        # Instancia do modelo host.
        $hosts = new Hosts();

        // Recuperando status
        $hostData = $hosts->where('cod_host', 1)->where('status', true)->first("status");

        // Verificando se encontra algum registro com o id encaminhado.
        if (!$hostData) {
            return $this->error('Host não encontrado.');
        }

        // Verificando se o host está ativo.
        if (!$hostData->status) {
            return $this->error('O host solicitado está inativo.');
        }

        // Modelo de macros de hosts.
        $macrosHosts = new MacrosHosts();

        // Recuperando macros.
        $macrosHostsDatas = $macrosHosts->where('cod_host_fk', 1)->get();

        if (!$macrosHostsDatas) {
            return $this->error('Nenhuma macro encontrada.');
        }

        return $this->success($macrosHostsDatas, "Macros recuperadas com sucesso.");
    }

    /**
     * Realiza a criação de hosts.
     *
     * @param Request $request
     * @return array<string>
     */
    public function register(Request $request)
    {
        try {
            # Instancia do modelo host.
            $hosts = new Hosts();

            // Efetuando a inclusão do host.
            $createHost = $hosts->register([
                "name" => $request->host_name
            ]);

            // Verifica se houve sucesso na inclusão do host.
            if ($createHost) {
                return $this->success($createHost, "Host criado com sucesso.");
            }

            return $this->error("Não foi possível criar o host $request->host_name.");
        } catch (Exception $error) {
            return $this->error("Erro ao tentar criar o host $request->host_name.");
        }
    }

    /**
     * Realiza a criação de interfaces para hosts.
     *
     * @param Request $request
     * @return array<string>
     */
    public function interfacesRegister(Request $request)
    {
        try {
            # Modelo de interfaces.
            $interfaces = new Interfaces();

            // Efetuando registro de dados.
            $createInterface = $interfaces->register([
                "name" => $request->name,
                "address" => $request->address,
                "port" => $request->port,
                "cod_host_fk" => $request->cod_host,
            ]);

            // Verificando se houve registro na base de dados.
            if ($createInterface) {
                return $this->success($createInterface, "Host criado com sucesso.");
            }

            return $this->error("Erro ao tentar criar a interface para o endereço $request->address.");
        } catch (Exception $error) {
            return $this->error("Erro ao tentar criar a interface para o endereço $request->address.");
        }
    }


    /**
     * Realiza o registro de macros para hosts.
     *
     * @param Request $request
     * @return array<string>
     */
    public function macrosRegister(Request $request)
    {
        try {
            # Modelo de Macros Hosts.
            $macrosHosts = new MacrosHosts();

            // Realizando o registro das macros na base.
            $createMacrosHosts = $macrosHosts->register([
                "cod_host_fk" => $request->cod_host,
                "macro" => $request->macro,
                "value" => $request->value,
                "type" => $request->type,
                "description" => $request->description
            ]);

            // Verificando se houve registro na base de dados.
            if ($createMacrosHosts) {
                return $this->success($createMacrosHosts, "Macro criada com sucesso.");
            }
            return $this->error("Não foi possível criar a macro $request->macro.");
        } catch (Exception $error) {
            return $this->error("Erro ao tentar criar a macro $request->macro.");
        }
    }
}
