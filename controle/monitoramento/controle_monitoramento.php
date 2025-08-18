<?php
// controle/monitoramento/controle_monitoramento.php
header('Content-Type: application/json');

// Chave da API WeatherAPI.com.
$apiKey = "ac194985c6b749509d8235429251308";

// Certifique-se de que a requisição é um GET e que o parâmetro de localização foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['localizacao'])) {
    $localizacao = urlencode($_GET['localizacao']); // Converte para ASCII, removendo acentos e caracteres especiais

    $lang = 'pt'; // Idioma para a resposta da API (português)
    
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
        echo json_encode(['erro' => $data['error']['message']]);
    } else {
        // Retorna a resposta completa da API para o frontend
        echo $response;
    }

} else {
    // Retorna erro se a requisição for inválida
    http_response_code(400);
    echo json_encode(['erro' => 'Requisição inválida.']);
}