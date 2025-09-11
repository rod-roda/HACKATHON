<?php
// Função helper para obter authorization header de forma robusta
function getAuthorizationHeader() {
    $authorization = null;
    
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    } else {
        // Fallback para CLI ou servidores que não suportam getallheaders()
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        }
    }
    
    return $authorization;
}
?>