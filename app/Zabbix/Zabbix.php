<?php


namespace App\Zabbix;

use GuzzleHttp\Client;

# --------------------------------------------------------------------
#   Class Zabbix:
#       Responsável por criar uma conexão com a API do Zabbix.
# --------------------------------------------------------------------
class Zabbix
{

    /**
     * Recebe o usuário de login na API.
     *
     * @var string
     */
    private string $username;

    /**
     * Recebe a senha de autenticação com a API.
     *
     * @var string
     */
    private string $password;

    /**
     * Recebe o token de autenticação.
     *
     * @var string
     */
    private string $token;

    /**
     * Recebe o local|endereço da api.
     *
     * @var string
     */
    private string $urlbase;

    /**
     * Recebe o valor de true ou false para se pode ser verificado o certificado ssl.
     *
     * @var boolean
     */
    private bool $verify;

    /**
     * Configura os valores de cada propriedade necessária.
     *
     * @param string $urlbase
     * @param string $username
     * @param string $password
     * @param boolean $verify
     */
    public function __construct(string $urlbase, string $token = "", string $username = "", string $password = "", bool $verify = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->urlbase = $urlbase;
        $this->token = $token;
        $this->verify = $verify;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }


    /**
     * Cria um cliente de conexão com a API do Zabbix.
     *
     * @return Client<resource|array>
     */
    protected function connection(): Client
    {
        // Criando cliente de conexão.
        $client = new Client([
            "base_uri" => $this->urlbase . "/api_jsonrpc.php",
            "verify" => $this->verify,
        ]);

        return $client;
    }

    /**
     * Retorna a versão da API do Zabbix.
     *
     * @return string
     */
    public function apiVersion(): string
    {
        // Realiza o request no
        $response = $this->connection()->post('', [
            'json' => [
                "jsonrpc" => "2.0",
                "method" => "apiinfo.version",
                "id" => 1,
                "auth" => null,
                "params" => []
            ],
        ]);

        // Recupera o conteúdo da response.
        $result = json_decode($response->getBody(), true);

        if (isset($result['result'])) {
            $version = $result['result'];
            return 'Versão do Zabbix: ' . $version;
        } else {
            return 'Erro na chamada da API: ' . $result['error']['message'];
        }
    }
}
