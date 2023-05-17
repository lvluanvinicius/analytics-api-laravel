<?php

use GuzzleHttp\Client;

if (!function_exists('getProxmoxData')) {

    function getProxmoxData(
        string $base_url,
        string $defaul_path,
        string $route = "/",
        array $headers = ['Content-Type' => 'application/json'],
        bool $sslVerify = false
    ) {
        $client = new Client([
            // Substitua pela URL da API do Proxmox
            'base_uri' => $base_url,
            // Desabilite a verificação de certificado SSL se necessário
            'verify' => $sslVerify,
        ]);

        dd($client);
        // Realizando request
        $response = $client->request('POST', $defaul_path . $route, [
            'headers' => $headers
        ]);


        $statusCode = $response->getStatusCode();
        $data = $response->getBody()->getContents();

        // Faça o processamento dos dados conforme necessário

        return $data;
    }
}
