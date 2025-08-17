<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "modelo/UserGame.php";
require_once "docs/codes/functions.php";

function insertScore(){
    $json = file_get_contents('php://input');
    $objJson = json_decode($json);

    $resposta = new stdClass();

    if(empty($objJson->jogo_nome)) return json_encode(error("O campo 'jogo_nome' é obrigatório", 400, $resposta));
    $jogo_nome = $objJson->jogo_nome;

    if(empty($objJson->resultado)) return json_encode(error("O campo 'resultado' é obrigatório", 400, $resposta));
    $resultado = $objJson->resultado;

    $userGame = new UserGame();

    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();
    if($token->validarToken($authorization)){
        $payload = $token->getPayload($authorization); //contem o ID
        $usuario_id = $payload->idUsuario;

        if($userGame->isUserGame($usuario_id)){
            if($userGame->updateScore($usuario_id, $resultado)){
                $resposta->cod = 1;
                $resposta->status = true;
                $resposta->msg = "Resultado atualizado!";
            }else{
                return json_encode(error("Erro no sistema", 500, $resposta));
            }
        }else{
            if($userGame->createScore($usuario_id, $jogo_nome, $resultado)){
                $resposta->cod = 1;
                $resposta->status = true;
                $resposta->msg = "Resultado criado!";
            }else{
                return json_encode(error("Erro no sistema", 500, $resposta));
            }
        }

    }else{
        $resposta->cod = 2;
        $resposta->status = false;
        $resposta->msg = "Token invalido!";
        $resposta->tokenRecebido = $authorization;
    }

    return json_encode($resposta);
}

function readById(){
    $resposta = new stdClass();

    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    
    $userGame = new UserGame();
    
    $token = new MeuTokenJWT();
    if($token->validarToken($authorization)){
        $payload = $token->getPayload($authorization); //contem o ID
        $usuario_id = $payload->idUsuario;

        if($userGame->isUserGame($usuario_id)){
            $dados = $userGame->readById($usuario_id);
            $resposta->cod = 1;
            $resposta->status = true;
            $resposta->msg = "Dados do UserGame encontrados!";
            $resposta->dados = $dados;
        }else{
            $resposta->cod = 404;
            $resposta->status = true;
            $resposta->msg = "Usuário não encontrado ou ainda não existente";
        }
    }else{
        $resposta->cod = 2;
        $resposta->status = false;
        $resposta->msg = "Token invalido!";
        $resposta->tokenRecebido = $authorization;
    }

    return json_encode($resposta);
}