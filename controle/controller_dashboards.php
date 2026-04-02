<?php
use Firebase\JWT\MeuTokenJWT;
require_once __DIR__ . '/../modelo/MeuTokenJWT.php';
require_once __DIR__ . '/../modelo/Banco.php';
require_once __DIR__ . '/../modelo/Dashboard.php';
require_once __DIR__ . '/../modelo/IaService.php';

// Criar nova atividade
function createAtividade() {
    header('Content-Type: application/json'); // Moved to top
    
    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();
    $resposta = new stdClass();
    $atividade = new AtividadeEcologica();

    // Pega o JSON enviado pelo front-end
    $dados = json_decode(file_get_contents("php://input"));

    if (!$dados) {
        $resposta->cod = 2;
        $resposta->status = false;
        $resposta->mensagem = "Dados inválidos ou ausentes";
        return json_encode($resposta);
    }

    if($token->validarToken($authorization)) {
        $payload = $token->getPayload($authorization);
        $usuario_id = $payload->idUsuario;

        $atividade->setUsuarioId($usuario_id);
        $atividade->setNomeAtividade($dados->nome_atividade);
        $atividade->setQuantidade($dados->quantidade);
        $atividade->setDataAtividade($dados->data_atividade);

        $emissao = 0;
        try {
            $iaService = new IaService();
            $pergunta = "Considere uma pessoa que realizou a seguinte atividade: " . 
                    match($dados->nome_atividade) {
                        'carro' => "dirigiu {$dados->quantidade} quilômetros de carro",
                        'energia' => "consumiu {$dados->quantidade} kWh de energia elétrica",
                        'aviao' => "viajou {$dados->quantidade} quilômetros de avião",
                        'carne' => "consumiu {$dados->quantidade} kg de carne bovina",
                        'gas' => "utilizou {$dados->quantidade} metros cúbicos de gás natural",
                        'onibus' => "viajou {$dados->quantidade} quilômetros de ônibus"
                    } . 
                    ". Calcule a pegada de carbono desta atividade usando médias e padrões conhecidos. Forneça apenas o valor numérico em kg de CO2 equivalente, sem explicações adicionais.";
            
            $emissao = floatval($iaService->gerarResposta($pergunta));
        } catch (Exception $e) {
            // Fallback calculation se a IA falhar (ex: rate limit, limite de requisições, erro de rede)
            $qtd = floatval($dados->quantidade);
            $emissao = match($dados->nome_atividade) {
                'carro' => $qtd * 0.192,
                'energia' => $qtd * 0.4,
                'aviao' => $qtd * 0.15,
                'carne' => $qtd * 27.0,
                'gas' => $qtd * 2.0,
                'onibus' => $qtd * 0.1,
                default => 0
            };
        }
        
        $atividade->setCarbonoEmitido($emissao);

        $atividade->create();

        $resposta->cod = 1;
        $resposta->status = true;
        $resposta->mensagem = "Atividade registrada!";

        return json_encode($resposta);
      
    } else {
        $resposta->cod = 2;
        $resposta->status = false;
        $resposta->msg = "Token invalido!";
        $resposta->tokenRecebido = $authorization;
        return json_encode($resposta);
    }
}
// Listar atividades
function readAtividades() {
    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();
    $resposta = new stdClass();
    $atividade = new AtividadeEcologica();

    // Pega o JSON enviado pelo front-end
    $dados = json_decode(file_get_contents("php://input"));


       if($token->validarToken($authorization)){
            $payload = $token->getPayload($authorization); //contem o ID
            $usuario_id = $payload->idUsuario;
            $dados = $atividade->readAll();
        $resposta->cod = 1;
        $resposta->status = true;
        $resposta->mensagem = "Atividades encontradas!";
        $resposta->dados = $dados;


    header("Content-Type: application/json");
    header("HTTP/1.1 200");
    return json_encode(value: $resposta);


      
      }else{
        $resposta->cod = 2;
        $resposta->status = false;
        $resposta->msg = "Token invalido!";
        $resposta->tokenRecebido = $authorization;
    }
}


// controller_dashboards.php


function readDashboardStats() {
    header('Content-Type: application/json');
    
    $headers = getallheaders();
    $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    $token = new MeuTokenJWT();
    $resposta = new stdClass();
    $atividade = new AtividadeEcologica();

    if($token->validarToken($authorization)){
        $payload = $token->getPayload($authorization);
        $usuario_id = $payload->idUsuario;

        $atividade = new AtividadeEcologica();
        $resposta = new stdClass();
        $resposta->cod = 1;
        $resposta->status = true;
        $resposta->mensagem = "Totais de carbono encontrados!";
        $resposta->dados = [
            "total" => $atividade->getTotalCarbono($usuario_id),
            "mes"   => $atividade->getTotalCarbonoMes($usuario_id),
            "quiz_acertos_mes" => $atividade->getAcertosQuizMes($usuario_id),
            "total_doado_mes" => $atividade->getTotalDoadoMes($usuario_id)
        ];

        echo json_encode($resposta);
        exit;

    }else{
        $resposta->cod = 2;
        $resposta->status = false;
        $resposta->msg = "Token invalido!";
        $resposta->tokenRecebido = $authorization;
        echo json_encode($resposta);
        exit;
    }
}

function readGraficosGerais() {
    header('Content-Type: application/json');
    
    try {
        $headers = getallheaders();
        $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
        $token = new MeuTokenJWT();
        $resposta = new stdClass();
        $atividade = new AtividadeEcologica();

        if(!$authorization) {
            throw new Exception("Token não fornecido");
        }

        if(!$token->validarToken($authorization)){
            throw new Exception("Token inválido");
        }

        $payload = $token->getPayload($authorization);
        $usuario_id = $payload->idUsuario;

        $resposta->cod = 1;
        $resposta->status = true;
        $resposta->mensagem = "Dados para gráficos";
        $resposta->dados = [
            "comparacao" => $atividade->getComparacaoCarbonoComPaises($usuario_id),
            "carbono_mes" => $atividade->getTotalCarbonoMesPorAno($usuario_id),
            "resumo" => $atividade->getResumoAtividadesPorUsuario($usuario_id),
            "doadores" => $atividade->getTopDoadores(5),
            "jogos" => $atividade->getTopJogos(5),
            "quizzes" => $atividade->getTopQuizzes(5),
        ];

        echo json_encode($resposta);
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

/**
 * Endpoint unificado: retorna stats + dados de gráficos em uma única resposta.
 * Reduz de 2+ requisições para 1 única chamada.
 */
function readDashboardCombo() {
    header('Content-Type: application/json');
    
    try {
        $headers = getallheaders();
        $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;
        $token = new MeuTokenJWT();

        if(!$authorization) {
            throw new Exception("Token não fornecido");
        }

        if(!$token->validarToken($authorization)){
            throw new Exception("Token inválido");
        }

        $payload = $token->getPayload($authorization);
        $usuario_id = $payload->idUsuario;

        $atividade = new AtividadeEcologica();

        $resposta = new stdClass();
        $resposta->cod = 1;
        $resposta->status = true;
        $resposta->mensagem = "Dados completos do dashboard";
        $resposta->dados = [
            // Stats (cards)
            "total" => $atividade->getTotalCarbono($usuario_id),
            "mes"   => $atividade->getTotalCarbonoMes($usuario_id),
            "quiz_acertos_mes" => $atividade->getAcertosQuizMes($usuario_id),
            "total_doado_mes" => $atividade->getTotalDoadoMes($usuario_id),
            // Gráficos
            "comparacao" => $atividade->getComparacaoCarbonoComPaises($usuario_id),
            "carbono_mes" => $atividade->getTotalCarbonoMesPorAno($usuario_id),
            "resumo" => $atividade->getResumoAtividadesPorUsuario($usuario_id),
            "doadores" => $atividade->getTopDoadores(5),
            "jogos" => $atividade->getTopJogos(5),
            "quizzes" => $atividade->getTopQuizzes(5),
        ];

        echo json_encode($resposta);
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}