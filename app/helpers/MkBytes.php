<?php


use Ramsey\Uuid\Type\Integer;

if (!function_exists('formatBytes')) {
    /**
     * Realiza a formatação e o cálculo dos valores de bytes.
     *
     * @param Integer|string $bytes
     * @return string
     */
    function formatBytes($bytes)
    {
        $bytes = intval($bytes);
        if ($bytes >= (1024 * 1024 * 1024)) {
            $result = round($bytes / (1024 * 1024 * 1024), 2) . ' GiB';
        } elseif ($bytes >= (1024 * 1024)) {
            $result = round($bytes / (1024 * 1024), 2) . ' MiB';
        } else {
            $result = round($bytes / 1024, 2) . ' KiB';
        }

        return $result;
    }
}


if (!function_exists('formatSpeed')) {
    function formatSpeed($speed)
    {
        $speed = intval($speed);
        if ($speed >= (1000 * 1000 * 1000)) {
            $result = round($speed / (1000 * 1000 * 1000), 2) . ' Gbps';
        } elseif ($speed >= (1000 * 1000)) {
            $result = round($speed / (1000 * 1000), 2) . ' Mbps';
        } else {
            $result = round($speed / 1000, 2) . ' Kbps';
        }

        return $result;
    }
}
