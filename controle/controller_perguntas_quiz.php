<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "modelo/Banco.php";
require_once "modelo/PerguntaQuiz.php";

function readById($id){
    $objResposta = new stdClass();
    $objPergunta = new PerguntaQuiz();
    
    $headers = getallheaders();
    // $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    // $token = new MeuTokenJWT();
    // if($token->validarToken($authorization)){
        $dados = $objPergunta->readById($id);
        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->mensagem = "Pergunta encontrada!";
        $objResposta->dados = $dados;
    // }else{
    //     $objResposta->cod = 2;
    //     $objResposta->status = false;
    //     $objResposta->mensagem = "Token invalido!";
    //     $objResposta->tokenRecebido = $authorization;
    // }

    header("Content-Type: application/json");
    header("HTTP/1.1 200");

    return json_encode($objResposta);
}

function read10Perguntas(){
    $numeros = range(1, 100);
    shuffle($numeros);
    $array_perguntas = array_slice($numeros, 0, 10);
    
    $objResposta = new stdClass();
    $objPergunta = new PerguntaQuiz();
    
    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();
    // if($token->validarToken($authorization)){
        $dados = $objPergunta->read10Perguntas($array_perguntas);
        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->mensagem = "Perguntas encontradas!";
        $objResposta->dados = $dados;
    // }else{
    //     $objResposta->cod = 2;
    //     $objResposta->status = false;
    //     $objResposta->mensagem = "Token invalido!";
    //     $objResposta->tokenRecebido = $authorization;
    // }

    header("Content-Type: application/json");
    header("HTTP/1.1 200");

    return json_encode($objResposta);
}

