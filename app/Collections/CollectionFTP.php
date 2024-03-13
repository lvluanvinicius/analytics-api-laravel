<?php

namespace App\Collections;

use App\Collections\Ftp\Connection;
use App\Exceptions\CollectionFTPException;
use Error;
use Exception;
use Illuminate\Support\Facades\Date;

class CollectionFTP
{
    /**
     * Recebe a conexão com o FTP.
     *
     * @var resource<\App\Collections\Ftp\Connection>
     */
    private $ftpConn;

    /**
     * Recebe o path de trabalho no FTP.
     *
     * @var string
     */
    private $workdir;

    /**
     * Recebe o nome do arquivo recuperado.
     *
     * @var [type]
     */
    private $fname;


    public function __construct()
    {
        // Criando conexão.
        $ftp = new Connection(env('FTP_STORAGE_HOST'), env('FTP_STORAGE_PORT'), env('FTP_STORAGE_USERNAME'), env('FTP_STORAGE_PASSWORD'));
        // Recuperando conexão.
        $this->ftpConn = $ftp->connect();
        // Recuperando PATH de trabalho.
        $this->workdir = env('FTP_STORAGE_ROOT_PATH');
    }

    /**
     * Retorna o nome do ultimo arquivo de coleta do inventory.
     *
     * @return void
     */
    public function getFileName(): void
    {
        $contents = ftp_nlist($this->ftpConn, $this->workdir);

        // Buscando ultimo arquivo da lista.
        $name = end($contents);

        // Valida se houve retorno na listagem de arquivos no FTP.
        !$name && throw new CollectionFTPException('Nenhum arquivo encontrado para coleta.');

        // Retornando valor apenas do arquivo.
        $this->fname = "ONU " . explode(' ', $name)[1];
    }


    /**
     * Recupera a data do nome do arquivo.
     *
     * @return date
     */
    public function getFileDate()
    {
        // Separando em lista o nome do arquivo.
        $listDates = explode('-', $this->fname);

        // Recuperando data.
        $y = substr($listDates[1], 0, 4); // Recupera o ano no nome do arquivo.
        $m = substr($listDates[1], 4, 2); // Recupera o mês no nome do arquivo.
        $d = substr($listDates[1], 6, 2); // Recupera o dia no nome do arquivo.

        // Recuperando Time/Hours
        $h = substr($listDates[2], 0, 2); // Recupera o hora no nome do arquivo.
        $i = substr($listDates[2], 2, 2); // Recupera o minuto no nome do arquivo.
        $s = substr($listDates[2], 4, 2); // Recupera o segundos no nome do arquivo.

        return date("$y-$m-$d $h:$i:$s"); // Retorna data formatada.
    }


    /**
     * Busca o arquivo de coleta.
     *
     * @return bool
     */
    public function getFile(): bool
    {
        try {
            // Monta o caminho para armazenamento do arquivo de coleta.
            $storage_path_and_file = storage_path() . env("APP_STORAGE_DIR") . "/" . $this->fname;
            // Monsta o caminho remoto no FTP onde está o arquivo de coleta.
            $remove_path_and_file = $this->workdir . "/" . $this->fname;

            if (!file_exists($storage_path_and_file)) {
                if (ftp_get($this->ftpConn, $storage_path_and_file, $remove_path_and_file, FTP_TEXT)) {
                    echo "Arquivo $this->fname baixado com sucesso como $this->fname.";
                    ftp_close($this->ftpConn); // Encerrando conexão.
                    return true;
                } else {
                    echo "Erro ao baixar o arquivo $this->fname.";
                    ftp_close($this->ftpConn); // Encerrando conexão.
                    return false;
                }
            } else {
                ftp_close($this->ftpConn); // Encerrando conexão.
                // Lança um erro se já existir o arquivo.
                throw new Error("Coleta do arquivo $this->fname, já existe.", 1100);
            }
        } catch (Error | Exception $error) {
            echo ($error->getMessage()) . PHP_EOL;
            ftp_close($this->ftpConn); // Encerrando conexão.
            return false;
        }
    }

    /**
     * Realiza a leitura dos dados no arquivo de coleta.
     *
     * @return array
     */
    public function getFileContent(): array
    {
        // Monta o caminho para armazenamento do arquivo de coleta.
        $storage_path_and_file = storage_path() . env("APP_STORAGE_DIR") . "/" . $this->fname;

        $handle = fopen($storage_path_and_file, 'r'); // Abrindo arquivo de coleta.
        $header = fgetcsv($handle, 0, ','); // Lendo csv.

        $data = array(); // Array para salvar os dados.
        // Lendo linha por linha e criado o array data.
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $data[] = array_combine($header, $row);
        }

        fclose($handle); // Fecha o arquivo.

        $newData = []; // Novo array para os dados separados.
        foreach ($data as $d) {

            // Auxiliares para valores padrões.
            $rx = 0.0;
            $tx = 0.0;
            $name = "";

            // Removendo valores nulos de TX e RX.
            ($d['Rx Power (dBm)'] == null || $d['Rx Power (dBm)'] == "" || $d['Rx Power (dBm)'] == "N/A") ? $rx = 0.0 : $rx = $d['Rx Power (dBm)'];
            ($d['Tx Power (dBm)'] == null || $d['Tx Power (dBm)'] == "" || $d['Tx Power (dBm)'] == "N/A") ? $tx = 0.0 : $tx = $d['Tx Power (dBm)'];

            // Removendo valores nulos de Name e inserindo um padrão DESCONHECIDO_+SERIAL_NUMBER.
            ($d['Name'] == null || $d['Name'] == "" || $d['Name'] == "N/A") ?
                $name = "DESCONHECIDO_" . $d['Serial Number'] : $name = $d['Name'];

            // Inserindo dados no novo array.
            array_push($newData, [
                "name" => $name,
                "serial_number" => $d['Serial Number'],
                "device" => $d['Device ID'],
                "port" => $d['Port'],
                "onuid" => intval($d['ID']),
                "rx" => floatval($rx),
                "tx" => floatval($tx),
                "collection_date" => $this->getFileDate(),
            ]);
        }

        return $newData;
    }
}