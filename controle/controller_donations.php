<?php
function getAccessToken(){
    $objResposta = new stdClass();
    
    $headers = getallheaders();
    // $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    // $token = new MeuTokenJWT();
    // if($token->validarToken($authorization)){

        $config = [
            "certificado" => "C:/xampp/htdocs/HACKATON/certificados/certificado_completo.pem",
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
            $objResposta->cod = 0;
            $objResposta->status = false;
            $objResposta->mensagem = "Erro CURL: " . curl_error($curl);
        } else {
            $dados = json_decode($response, true);

            if (isset($dados['access_token'])) {
                $objResposta->cod = 1;
                $objResposta->status = true;
                $objResposta->mensagem = "Token gerado com sucesso!";
                $objResposta->access_token = $dados['access_token'];
            } else {
                $objResposta->cod = 0;
                $objResposta->status = false;
                $objResposta->mensagem = "Erro ao gerar token";
                $objResposta->resposta_api = $dados;
            }
        }

        curl_close($curl);

    // }else{
    //     $objResposta->cod = 2;
    //     $objResposta->status = false;
    //     $objResposta->mensagem = "Token invalido!";
    //     $objResposta->tokenRecebido = $authorization;
    // }

    header("Content-Type: application/json");
    header("HTTP/1.1 200");
    echo json_encode($objResposta);
}

function postGerarCodigo() {
    $objResposta = new stdClass();

    // Lê o corpo da requisição apenas uma vez
    $rawBody = file_get_contents("php://input");
    $body = json_decode($rawBody, true);

    if (empty($body['token']) || empty($body['valor'])) {
        $objResposta->cod = 0;
        $objResposta->status = false;
        $objResposta->mensagem = "Token e valor são obrigatórios no corpo da requisição!";
        header("Content-Type: application/json");
        echo json_encode($objResposta);
        return;
    }

    $config = [
        "certificado" => "C:/xampp/htdocs/HACKATON/certificados/certificado_completo.pem",
        "token" => $body['token']
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
    echo json_encode($objResposta);
}
