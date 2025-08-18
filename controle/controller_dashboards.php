<?php
use Firebase\JWT\MeuTokenJWT;
require_once __DIR__ . '/../modelo/MeuTokenJWT.php';
require_once __DIR__ . '/../modelo/Banco.php';
require_once __DIR__ . '/../modelo/Dashboard.php';
require_once __DIR__ . '/../modelo/IaService.php';

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

    // Calcula a emissão usando IA
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
    $atividade->setCarbonoEmitido($emissao);

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


// controller_dashboards.php


function readDashboardStats() {
        $usuarioId = 1; // pegar do token futuramente

    $atividade = new AtividadeEcologica();
    $resposta = new stdClass();
    $resposta->cod = 1;
    $resposta->status = true;
    $resposta->mensagem = "Totais de carbono encontrados!";
$resposta->dados = [
    "total" => $atividade->getTotalCarbono(),
    "mes"   => $atividade->getTotalCarbonoMes(), // <- dado total do mês atual (pode manter)
    "quiz_acertos_mes" => $atividade->getAcertosQuizMes($usuarioId),
    "total_doado_mes" => $atividade->getTotalDoadoMes($usuarioId)
];


    header("Content-Type: application/json");
    echo json_encode($resposta);
    exit;
}

function readGraficosGerais() {
    $atividade = new AtividadeEcologica();
    $usuarioId = 1; // pegar do token futuramente

    $resposta = new stdClass();
    $resposta->cod = 1;
    $resposta->status = true;
    $resposta->mensagem = "Dados para gráficos";

    $resposta->dados = [
        "comparacao" => $atividade->getComparacaoCarbonoComPaises($usuarioId),
        "carbono_mes" => $atividade->getTotalCarbonoMesPorAno($usuarioId),
        "resumo" => $atividade->getResumoAtividadesPorUsuario($usuarioId),
        "doadores" => $atividade->getTopDoadores(5),
        "jogos" => $atividade->getTopJogos(5),
        "quizzes" => $atividade->getTopQuizzes(5),
     
    ];

    header("Content-Type: application/json");
    echo json_encode($resposta);
    exit;
}
