<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hosts;
use App\Traits\ApiResponser;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;

class ProxmoxController extends Controller
{
    use ApiResponser;

    /**
     * Método Construtor.
     */
    function __construct()
    {
        // Cria a string de token para autorização.
        // $this->sendToken = 'PVEAPIToken=' . env('PROXMOX_PVEAPITOKEN') . "=" . env('PROXMOX_SECRET');
    }

    /**
     * Realiza o login e recupera o token.
     *
     * @param string $base_url
     * @param array $data
     * @return string
     */
    private function loginProxmox(string $base_url, array $data): string
    {
        try {
            $client = new Client([
                'base_uri' => $base_url,
                'verify' => false,
            ]);

            // Fazendo a requisição de login
            $response = $client->post('/api2/json/access/ticket', [
                'json' => [
                    'username' => $data[0],
                    'password' => $data[1],
                ],
            ]);

            // Obtendo o token de autenticação
            $data = json_decode($response->getBody(), true);
            $token = $data['data']['ticket'];

            return $token;
        } catch (Exception $error) {
            return false;
        }
    }

    public function requestService(string $base_url, string $ticket, string $authorization, $path)
    {
        $client = new Client([
            'base_uri' => $base_url,
            'verify' => false,
        ]);

        // Fazendo a requisição de login
        $response = $client->get('/api2/json' . $path, [
            'headers' => [
                'Authorization' => $authorization,
                'Cookie' => "PVEAuthCookie=" . $ticket,
            ],
        ]);

        // Obtendo o token de autenticação
        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function requestApp(Request $request)
    {
        // Verifica se o parâmetro obrigatório de proxmox_path foi informado.
        if (!$request->has('proxmox_path')) {
            return $this->error("O parâmetro 'proxmox_path' é obrigatório.", 200);
        }

        // Verifica se o parâmetro obrigatório de address foi informado.
        if (!$request->has('address')) {
            return $this->error("O parâmetro 'address' é obrigatório.", 200);
        }


        // Verifica se o parâmetro obrigatório de port foi informado.
        if (!$request->has('port')) {
            return $this->error("O parâmetro 'port' é obrigatório.", 200);
        }

        // Verificando se o token pve foi encaminhado no header.
        if (!$request->headers->has('pvetoken')) {
            return $this->error("Token obrigatório. Envie em 'pvetoken' no header.", 200);
        }

        // // Recupera as queries.
        $queries = $request->query();

        // Recupera ao location.
        $proxmoxBasePath = "https://" . $queries['address'] . ":" . $queries['port'];

        // Realizando login e recuperando token.
        $ticket = $this->loginProxmox($proxmoxBasePath, [$request->username, $request->password]);

        // Verificando se houve sucesso no login com o proxmox api.
        if (!$ticket) {
            return $this->error("Houve um erro ao tentar realizar login. Usuário ou senha estão incorretos.", 401);
        }

        // Realizar request.
        $requestResponse = $this->requestService($proxmoxBasePath, $ticket, $request->headers->get('pvetoken'), $queries['proxmox_path']);

        return $this->success($requestResponse['data'], null);
    }
}
