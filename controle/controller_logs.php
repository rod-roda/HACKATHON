<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "modelo/Banco.php";
require_once "modelo/Log.php";

function registrarLog($acao = '', $envio = [], $resposta = []) {
    $objResposta = new stdClass();
    $objLog = new Log();

    if(!empty($acao) && !empty($envio) && !empty($resposta)){
        $sucesso = $objLog->registrarLog($acao, json_encode($envio), json_encode($resposta));
        if ($sucesso) {
            $objResposta->cod = 1;
            $objResposta->status = true;
            $objResposta->mensagem = "Log registrado com sucesso!";
        } else {
            $objResposta->cod = 0;
            $objResposta->status = false;
            $objResposta->mensagem = "Erro ao registrar log!";
        }
    }else{
        $objResposta->cod = 0;
        $objResposta->status = false;
        $objResposta->mensagem = "Dados não enviados para registrar o Log!";
    }

    header("Content-Type: application/json");
    header("HTTP/1.1 200");

    return json_encode($objResposta);
}

function listarLogs() {
    $objResposta = new stdClass();
    $objLog = new Log();

    $headers = getallheaders();
    $authorization = $headers['Authorization'] ?? null;
    $token = new MeuTokenJWT();
    if ($token->validarToken($authorization)) {
        $dados = $objLog->listarTodos();
        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->mensagem = "Logs encontrados!";
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

function listarLogsPorAcao() {
    $objResposta = new stdClass();
    $objLog = new Log();

    $dados = json_decode(file_get_contents("php://input"), true);
    $acao = $dados['acao'] ?? '';

    $headers = getallheaders();
    $authorization = $headers['Authorization'] ?? null;
    $token = new MeuTokenJWT();
    if ($token->validarToken($authorization)) {
        $logs = $objLog->listarPorAcao($acao);
        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->mensagem = "Logs filtrados pela ação!";
        $objResposta->dados = $logs;
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
