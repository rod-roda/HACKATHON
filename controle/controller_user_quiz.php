<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/MeuTokenJWT.php";
require_once "modelo/UserQuiz.php";
require_once "docs/codes/functions.php";

function insertScore(){
    $json = file_get_contents('php://input');
    $objJson = json_decode($json);

    $resposta = new stdClass();

    if(empty($objJson->quiz_nome)) return json_encode(error("O campo 'quiz_nome' é obrigatório", 400, $resposta));
    $quiz_nome = $objJson->quiz_nome;

    if(empty($objJson->pontuacao)) return json_encode(error("O campo 'pontuacao' é obrigatório", 400, $resposta));
    $pontuacao = $objJson->pontuacao;

    if(empty($objJson->qtdRespondidas)) return json_encode(error("O campo 'qtdRespondidas' é obrigatório", 400, $resposta));
    $qtdRespondidas = $objJson->qtdRespondidas;

    if(empty($objJson->qtdCorretas)) return json_encode(error("O campo 'qtdRespondidas' é obrigatório", 400, $resposta));
    $qtdCorretas = $objJson->qtdCorretas;

    $userQuiz = new UserQuiz();

    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();
    if($token->validarToken($authorization)){
        $payload = $token->getPayload($authorization); //contem o ID
        $usuario_id = $payload->idUsuario;
        
        if($userQuiz->isUserQuiz($usuario_id)){
            if($userQuiz->updateScore($usuario_id, $pontuacao, $qtdRespondidas, $qtdCorretas)){
                $resposta->cod = 1;
                $resposta->status = true;
                $resposta->msg = "Dados do Quiz atualizados!";
            }else{
                return json_encode(error("Erro no sistema", 500, $resposta));
            }
        }else{
            if($userQuiz->createScore($usuario_id, $quiz_nome, $pontuacao, $qtdRespondidas, $qtdCorretas)){
                $resposta->cod = 1;
                $resposta->status = true;
                $resposta->msg = "Dados do Quiz criados!";
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