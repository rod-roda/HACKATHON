<?php
header('Content-Type = application/json');
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "modelo/Banco.php";
require_once "modelo/Usuario.php";
require_once "docs/codes/crypto.php";

function cadastrar(){
    $json = file_get_contents('php://input');
    $objJson = json_decode($json);

    $resposta = new stdClass();

    if(empty($objJson->nome)) return json_encode(error("O campo 'nome' é obrigatório", 400, $resposta));
    $nome = $objJson->nome;

    if(empty($objJson->email)) return json_encode(error("O campo 'email' é obrigatório", 400, $resposta));
    $email = $objJson->email;

    if(empty($objJson->senha)) return json_encode(error("O campo 'senha' é obrigatório", 400, $resposta));
    $senha = $objJson->senha;
    if(mb_strlen($senha) < 8) return json_encode(error("A senha deve ter pelo menos 8 caracteres.", 400, $resposta));

    if(empty($objJson->cpf)) return json_encode(error("O campo 'cpf' é obrigatório", 400, $resposta));
    $cpf = $objJson->cpf;

    if(!validarCPF($cpf)){
        return json_encode(error("O campo 'cpf' deve conter um CPF válido", 400, $resposta));
    }

    $senha_hash = criptografar($senha);
    $usuario = new Usuario();
    
    if($usuario->isUser($email)){
        return json_encode(error("Usuário já cadastrado", 400, $resposta));
    }

    if($usuario->cadastrar($nome, $email, $senha_hash, $cpf)){
        $objToken = new MeuTokenJWT();
        $claims = new stdClass();

        $claims->email = $email;

        $token = $objToken->gerarToken($claims);

        $resposta->cod = 1;
        $resposta->status = true;
        $resposta->msg = "Cadastrado com sucesso!";
        $resposta->token = $token;

        return json_encode($resposta);
    }

    return json_encode(error("Erro no sistema", 500, $resposta));
}

function logar(){
    $json = file_get_contents('php://input');
    $objJson = json_decode($json);

    $resposta = new stdClass();

    if(empty($objJson->email)) return json_encode(error("O campo 'email' é obrigatório", 400, $resposta));
    $email = $objJson->email;

    if(empty($objJson->senha)) return json_encode(error("O campo 'senha' é obrigatório", 400, $resposta));
    $senha = $objJson->senha;

    $usuario = new Usuario();
    if(!($usuario->isUser($email))){
        return json_encode(error("E-mail incorreto", 400, $resposta));
    }

    $senha_hash = $usuario->consultarSenha($email);
    if(!($senha == descriptografar($senha_hash))){
        return json_encode(error("Senha incorreta", 400, $resposta));
    }

    if($usuario->logar($email, $senha_hash)){
        $objToken = new MeuTokenJWT();
        $claims = new stdClass();
        $claims->email = $email;
        $token = $objToken->gerarToken($claims);

        $resposta->cod = 1;
        $resposta->status = true;
        $resposta->msg = "Logado com sucesso!";
        $resposta->token = $token;

        return json_encode($resposta);
    }

    return json_encode(error("Erro no sistema", 500, $resposta));
}

/*----------------------------------- UTILIDADES -----------------------------------*/

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) != 11) {
        return false;
    }

    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }
        $digito = ((10 * $soma) % 11) % 10;
        if ($cpf[$i] != $digito) {
            return false;
        }
    }

    return true;
}

function error($msg, $cod, $resposta = new stdClass()){
    $resposta->cod = $cod;
    $resposta->status = false;
    $resposta->msg = $msg;
    return $resposta;
}