<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "controle/controller_logs.php";

function monitoringApiCall($localizacao){
    $apiKey = "ac194985c6b749509d8235429251308";

    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();

    if(!$token->validarToken($authorization)){
        http_response_code(401);
        return json_encode(['erro' => 'Token Inválido']);
    }

    header('Content-Type: application/json');
    $lang = 'pt';

    $q = $localizacao; 
    $osmUrl = "https://nominatim.openstreetmap.org/search?format=json&limit=1&q=" . rawurlencode($q);

    $ch = curl_init($osmUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:118.0) Gecko/20100101 Firefox/118.0',
        CURLOPT_HTTPHEADER => [
            'Referer: https://seu-dominio-ou-app.local',
            'Accept: application/json',
            'Accept-Language: pt-BR'
        ],
        CURLOPT_TIMEOUT => 20,
        CURLOPT_FOLLOWLOCATION => true,
    ]);
    $osmBody = curl_exec($ch);
    $osmErr  = curl_error($ch);
    $osmCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($osmErr) {
        http_response_code(502);
        return json_encode(['erro' => "Falha ao consultar Nominatim: $osmErr"]);
    }
    if ($osmCode !== 200) {
        http_response_code($osmCode);
        return json_encode(['erro' => "Nominatim respondeu HTTP $osmCode"]);
    }

    $osm = json_decode($osmBody, true);
    if (!is_array($osm) || count($osm) === 0 || !isset($osm[0]['lat'], $osm[0]['lon'])) {
        http_response_code(404);
        return json_encode(['erro' => 'Não foi possível obter a localização da cidade informada']);
    }

    $lat = $osm[0]['lat'];
    $lon = $osm[0]['lon'];

    registrarLog("GET - ".$osmUrl, json_encode(['localizacao_input'=>$q]), $osmBody);

    $coords = $lat . ',' . $lon;
    $wxUrl  = "https://api.weatherapi.com/v1/forecast.json?key={$apiKey}&q=" . rawurlencode($coords) . "&days=3&lang={$lang}";

    $ch = curl_init($wxUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $wxBody = curl_exec($ch);
    $wxErr  = curl_error($ch);
    $wxCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($wxErr) {
        http_response_code(502);
        return json_encode(['erro' => "Falha ao consultar WeatherAPI: $wxErr"]);
    }
    if ($wxCode !== 200) {
        $tmp = json_decode($wxBody, true);
        $msg = $tmp['error']['message'] ?? "HTTP $wxCode";
        http_response_code($wxCode);
        return json_encode(['erro' => $msg]);
    }

    registrarLog("GET - ".$wxUrl, json_encode([
        'localizacao_input' => $q,
        'coords'            => $coords,
        'lang'              => $lang
    ]), $wxBody);

    $out = json_decode($wxBody, true);
    $out['_lookup'] = [
        'input'   => $q,
        'lat'     => $lat,
        'lon'     => $lon,
        'resolved'=> ($osm[0]['display_name'] ?? '')
    ];
    return json_encode($out);
}
