<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "modelo/Banco.php";
require_once "modelo/Usuario.php";

function cadastrar(){
    $json = file_get_contents('php://input');
    $objJson = json_decode($json);

    $resposta = new stdClass();

    if(empty($objJson->nome)){
        $resposta->cod = 400;
        $resposta->status = false;
        $resposta->msg = "O campo 'nome' é obrigatório";
        return json_encode($resposta);
    }
    $nome = $objJson->nome;

    if(empty($objJson->email)){
        $resposta->cod = 400;
        $resposta->status = false;
        $resposta->msg = "O campo 'email' é obrigatório";
        return json_encode($resposta);
    }
    $email = $objJson->email;

    if(empty($objJson->senha)){
        $resposta->cod = 400;
        $resposta->status = false;
        $resposta->msg = "O campo 'senha' é obrigatório";
        return json_encode($resposta);
    }
    $senha = $objJson->senha;

    if(empty($objJson->cpf)){
        $resposta->cod = 400;
        $resposta->status = false;
        $resposta->msg = "O campo 'cpf' é obrigatório";
        return json_encode($resposta);
    }
    $cpf = $objJson->cpf;

    return json_encode($objJson);
}