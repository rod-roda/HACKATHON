<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "modelo/Banco.php";
require_once "modelo/Donation.php";
function getAccessToken() {
    $config = [
        "certificado" => "C:/xampp/htdocs/HACKATHON/certificados/certificado_completo.pem",
        "client_id" => "Client_Id_040e1ea39273b7d69e13ba2e1a1fbf363c9d93ca",
        "client_secret" => "Client_Secret_5ebd64768c8002a55f98427382ba212880b6fc9d"
    ];

    $autorizacao = base64_encode($config["client_id"] . ":" . $config["client_secret"]);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://pix.api.efipay.com.br/oauth/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '{"grant_type": "client_credentials"}',
        CURLOPT_SSLCERT => $config["certificado"],
        CURLOPT_SSLCERTPASSWD => "",
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic $autorizacao",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        curl_close($curl);
        return false; // erro de conexão
    }

    curl_close($curl);

    $dados = json_decode($response, true);

    if (isset($dados['access_token'])) {
        return $dados['access_token']; // retorna somente o token
    }

    return false; // caso a API não retorne o token
}

function postGerarCodigo() {
    $objResposta = new stdClass();

    $rawBody = file_get_contents("php://input");
    $body = json_decode($rawBody, true);

    if (empty($body['valor'])) {
        $objResposta->cod = 0;
        $objResposta->status = false;
        $objResposta->mensagem = "O campo 'valor' é obrigatório!";
        header("Content-Type: application/json");
        return json_encode($objResposta);
    }

    // Obtém token via função
    $token = getAccessToken();
    if (!$token) {
        $objResposta->cod = 0;
        $objResposta->status = false;
        $objResposta->mensagem = "Erro ao gerar token de acesso!";
        header("Content-Type: application/json");
        return json_encode($objResposta);
    }

    $config = [
        "certificado" => "C:/xampp/htdocs/HACKATHON/certificados/certificado_completo.pem",
        "token" => $token
    ];

    $dados = [
        "calendario" => ["expiracao" => 3600],
        "devedor" => ["cpf" => "12345678909", "nome" => "Francisco da Silva"],
        "valor" => ["original" => number_format((float)$body['valor'], 2, '.', '')],
        "chave" => "23a9924f-1f02-4cab-ab9d-82bf41565451",
        "solicitacaoPagador" => "Cobrança dos serviços prestados."
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://pix.api.efipay.com.br/v2/cob",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($dados, JSON_UNESCAPED_UNICODE),
        CURLOPT_SSLCERT => $config["certificado"],
        CURLOPT_SSLCERTPASSWD => "",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer {$config['token']}",
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $objResposta->cod = 0;
        $objResposta->status = false;
        $objResposta->mensagem = "Erro CURL: " . curl_error($curl);
        $objResposta->resposta_api = null;
    } else {
        $dadosResp = json_decode($response, true);

        if (isset($dadosResp['pixCopiaECola'])) {
            $objResposta->cod = 1;
            $objResposta->status = true;
            $objResposta->mensagem = "Pix gerado com sucesso!";
            $objResposta->pixCopiaECola = $dadosResp['pixCopiaECola'];
        } else {
            $objResposta->cod = 0;
            $objResposta->status = false;
            $objResposta->mensagem = "Erro ao gerar PIX";
            $objResposta->resposta_api = $dadosResp;
        }
    }

    curl_close($curl);

    header("Content-Type: application/json");
    return json_encode($objResposta);
}
function registrarPix() {
    $json = file_get_contents('php://input');
    $objJson = json_decode($json);

    $resposta = new stdClass();

    if (empty($objJson->usuarioId)) {
        return json_encode(error("O campo 'usuarioId' é obrigatório", 400, $resposta));
    }
    $usuarioId = $objJson->usuarioId;

    if (empty($objJson->valor) || !is_numeric($objJson->valor) || $objJson->valor <= 0) {
        return json_encode(error("O campo 'valor' é inválido", 400, $resposta));
    }
    $valor = $objJson->valor;

    $donation = new Donation();
    if ($donation->cadastrar($usuarioId, $valor)) {
        $resposta->cod = 1;
        $resposta->status = true;
        $resposta->msg = "Doação cadastrada com sucesso!";
        return json_encode($resposta);
    }

    return json_encode(error("Erro ao cadastrar a doação", 500, $resposta));
}