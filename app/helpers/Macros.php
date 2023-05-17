<?php

/**
 * Recupera valores de macros.
 */


if (!function_exists('replace_macros')) {
    function replace_macros($macros, string $key)
    {
        // Array auxiliar para carregamento de macros.
        $newMacrosHost = [];
        // Tratando macros.
        foreach ($macros as $mh) {
            array_push($newMacrosHost, "$mh->macro:$mh->value");
        }

        /**
         * Recupera os valores de macros a partir do novo array acima.
         * Sendo sempre um padrão:
         *     -> Index 0 sendo variável;
         *     -> Index 1 sendo valor;
         */
        foreach ($newMacrosHost as $mcdb) {
            $replaceText = explode(":", $mcdb);
            if ($replaceText[0] == $key) {
                return $replaceText[1];
            }
        }
    }
}
