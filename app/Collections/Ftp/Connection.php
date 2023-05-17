<?php

namespace App\Collections\Ftp;

use Error;
use Exception;

class Connection
{

    private $host;
    private $port;
    private $username;
    private $password;


    public function __construct($host, $port, $username, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    private function create_connect()
    {
        $connection = ftp_connect($this->host, $this->port, 30) or false;

        if ($connection) {
            return $connection;
        }

        return false;
    }


    private function login()
    {
        $conn = $this->create_connect();
        $login = ftp_login($conn, $this->username, $this->password);

        if ($login) {
            return $conn;
        }
        return false;
    }

    /**
     * Realiza a conexão e retorna false se não conectar e a própria conexão de conectar.
     *
     * @return Resource
     */
    public function connect()
    {
        try {
            // Realizando login
            if (!$conn = $this->login()) {
                throw new \Error("Falha ao efetuar login no FTP.");
            }

            // Ativando modo passivo.
            ftp_pasv($conn, true);

            return $conn;
        } catch (Exception | Error $error) {
            var_dump($error->getMessage());
        }
    }
}
