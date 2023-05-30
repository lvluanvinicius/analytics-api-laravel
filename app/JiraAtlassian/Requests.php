<?php

namespace App\JiraAtlassian;

use GuzzleHttp\Client;

class Requests
{
    /**
     * Recebe o valor com a url da api do Jira Atlassian.
     *
     * @var string
     */
    private string $url;

    /**
     * Recebe o valor com email de acesso a api do Jira Atlassian.
     *
     * @var string
     */
    private string $email;

    /**
     * Recebe o valor com o token de acesso a api do Jira Atlassian.
     *
     * @var string
     */
    private string $apiToken;

    /**
     * Recebe o valor de true ou false para se pode ser verificado o certificado ssl.
     *
     * @var boolean
     */
    private bool $verify;

    public function __construct($url, $email, $apiToken, $verify = true)
    {
        $this->url = $url;
        $this->email = $email;
        $this->apiToken = $apiToken;
        $this->verify = $verify;
    }

    public function connect()
    {

        $client = new Client([
            "base_uri" => $this->url . "/",
            "verify" => $this->verify,
        ]);

        return $client;
    }

    public function reqSla()
    {
        $locations_data = json_decode(file_get_contents(__DIR__ . '/files/locations.json'), true);

        $response = $this->connect()->get('servicedesk/3/queue/13/issue', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode("$this->email:$this->apiToken"),
            ],
        ]);

        // Convertendo dados.
        $data = json_decode($response->getBody(), true);


        $listCidade = [];

        foreach ($data['values'] as $location) {
            $issueType = $location['fields']['issuetype']['name'];
            $priority = $location['fields']['priority']['name'];
            $labels = $location['fields']['labels'];

            foreach ($locations_data as $city) {
                if (in_array($city['name'], $labels)) {
                    $listCidade[] = [
                        'city_name' => $city['name'],
                        'issueType' => $issueType,
                        'priority' => $priority,
                        'latitude' => $city['lat'],
                        'longitude' => $city['lon']
                    ];
                }
            }
        }

        return $listCidade;
    }
}
