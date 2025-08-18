<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";

function monitoringApiCall($localizacao){
    $apiKey = "ac194985c6b749509d8235429251308";
    
    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();
    
    if($token->validarToken($authorization)){
        header('Content-Type: application/json');
        $lang = 'pt'; // Idioma para a resposta da API (português)

        $localizacao = urlencode($localizacao);
        
        // Altera a API para buscar a previsão de 3 dias
        // Endpoint de forecast (previsão) com 3 dias de antecedência.
        $apiUrl = "https://api.weatherapi.com/v1/forecast.json?key={$apiKey}&q={$localizacao}&days=3&lang={$lang}";

        // Inicializa a sessão cURL
        $ch = curl_init();

        // Define as opções do cURL
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Executa a requisição e obtém a resposta
        $response = curl_exec($ch);

        // Fecha a sessão cURL
        curl_close($ch);

        // Decodifica a resposta JSON da API
        $data = json_decode($response, true);

        // Verifica se a API retornou um erro
        if (isset($data['error'])) {
            // Retorna o erro da API para o frontend
            http_response_code(404);
            return json_encode(['erro' => $data['error']['message']]);
        } else {
            return $response;
        }
    }else{
        return json_encode(['erro' => 'Token Inválido']);
    }
}