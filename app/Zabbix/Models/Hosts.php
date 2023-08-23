<?php


namespace App\Zabbix\Models;

use App\Zabbix\Zabbix;

class Hosts extends Zabbix
{
    public string $version = "2.0";

    /**
     * Recupera dados de uma requisição para metodo host.get;
     *
     * @param array $params
     * @return void
     */
    public function request(array $params)
    {
        // Setando versão do jsonrpc.
        $params["jsonrpc"] = $this->version;

        // Verifica se usuário ou senha foi informado.
        if (!$this->__get("username") == "" && !$this->__get("username") == "") {
            $params["params"]["username"] = $this->__get("username");
            $params["params"]["password"] = $this->__get("password");
        } else {
            $params["auth"] = $this->__get("token");
        }
                // Realiza o request.
        $response = $this->connection()->post('', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $params,
        ]);

        // Recupera o conteúdo da response.
        $history = json_decode($response->getBody(), true);

        return $history;
    }
}
