<?php
use Firebase\JWT\MeuTokenJWT;
require_once __DIR__ . '/../modelo/MeuTokenJWT.php';
require_once __DIR__ . '/../modelo/Banco.php';
require_once __DIR__ . '/../modelo/Dashboard.php';

// Criar nova atividade
function createAtividade() {
    $objResposta = new stdClass();
    $atividade = new AtividadeEcologica();

    // Pega o JSON enviado pelo front-end
    $dados = json_decode(file_get_contents("php://input"));

    if (!$dados) {
        $objResposta->cod = 2;
        $objResposta->status = false;
        $objResposta->mensagem = "Dados inválidos ou ausentes";
        echo json_encode($objResposta);
        exit;
    }

    $atividade->setUsuarioId(1); // depois pegar do token
    $atividade->setNomeAtividade($dados->nome_atividade);
    $atividade->setQuantidade($dados->quantidade);
    $atividade->setDataAtividade($dados->data_atividade);

    $atividade->create();

    $objResposta->cod = 1;
    $objResposta->status = true;
    $objResposta->mensagem = "Atividade registrada!";

    header("Content-Type: application/json");
    header("HTTP/1.1 200 OK");
    echo json_encode($objResposta);
    exit;
}

// Listar atividades
function readAtividades() {
    $objResposta = new stdClass();
    $atividade = new AtividadeEcologica();

    $headers = getallheaders();
//    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
//$token = new MeuTokenJWT();

  //  if($token->validarToken($authorization)) {
        $dados = $atividade->readAll();
        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->mensagem = "Atividades encontradas!";
        $objResposta->dados = $dados;
  //  } else {
        $objResposta->cod = 2;
        $objResposta->status = false;
     //   $objResposta->mensagem = "Token inválido!";
    //    $objResposta->tokenRecebido = $authorization;
   // }

    header("Content-Type: application/json");
    header("HTTP/1.1 200");
    return json_encode($objResposta);
}
