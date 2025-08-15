<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "modelo/Banco.php";
require_once "modelo/AtividadeEcologica.php";

// Criar nova atividade
function createAtividade($dados) {
    $objResposta = new stdClass();
    $atividade = new AtividadeEcologica();

    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();

    if($token->validarToken($authorization)) {
        $atividade->setUsuarioId($dados->usuario_id);
        $atividade->setNomeAtividade($dados->nome_atividade);
        $atividade->setQuantidade($dados->quantidade);
        $atividade->setCarbonoEmitido($dados->carbono_emitido);
        $atividade->setDataAtividade($dados->data_atividade);

        $atividade->create();

        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->mensagem = "Atividade registrada!";
        $objResposta->dados = $atividade;
    } else {
        $objResposta->cod = 2;
        $objResposta->status = false;
        $objResposta->mensagem = "Token inválido!";
        $objResposta->tokenRecebido = $authorization;
    }

    header("Content-Type: application/json");
    header("HTTP/1.1 200");
    return json_encode($objResposta);
}

// Listar atividades
function readAtividades() {
    $objResposta = new stdClass();
    $atividade = new AtividadeEcologica();

    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();

    if($token->validarToken($authorization)) {
        $dados = $atividade->readAll();
        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->mensagem = "Atividades encontradas!";
        $objResposta->dados = $dados;
    } else {
        $objResposta->cod = 2;
        $objResposta->status = false;
        $objResposta->mensagem = "Token inválido!";
        $objResposta->tokenRecebido = $authorization;
    }

    header("Content-Type: application/json");
    header("HTTP/1.1 200");
    return json_encode($objResposta);
}
