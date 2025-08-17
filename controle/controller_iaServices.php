<?php
require_once "modelo/IaService.php";

function responderPerguntaSustentabilidade() {
    $objResposta = new stdClass();

    try {
        $dados = json_decode(file_get_contents("php://input"), true);
        $pergunta = $dados['pergunta'] ?? '';

        if (empty($pergunta)) {
            $objResposta->cod = 0;
            $objResposta->status = false;
            $objResposta->mensagem = "Pergunta não informada.";
        } else {
            $assistente = new IaService();
            $resposta = $assistente->gerarResposta($pergunta);

            $objResposta->cod = 1;
            $objResposta->status = true;
            $objResposta->mensagem = "Resposta gerada com sucesso!";
            $objResposta->resposta = $resposta;
        }
    } catch (Exception $e) {
        $objResposta->cod = 0;
        $objResposta->status = false;
        $objResposta->mensagem = "Erro ao processar a pergunta.";
        $objResposta->erro = $e->getMessage();
    }

    header("Content-Type: application/json");
    header("HTTP/1.1 200");

    return json_encode($objResposta);
}
