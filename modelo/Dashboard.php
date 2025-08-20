<?php
require_once "modelo/Banco.php";

class AtividadeEcologica implements JsonSerializable {
    private $id;
    private $usuario_id;
    private $nome_atividade;
    private $quantidade;
    private $carbono_emitido;
    private $data_atividade;

    public function jsonSerialize() {
        $obj = new stdClass();
        $obj->id = $this->getId();
        $obj->usuario_id = $this->getUsuarioId();
        $obj->nome_atividade = $this->getNomeAtividade();
        $obj->quantidade = $this->getQuantidade();
        $obj->carbono_emitido = $this->getCarbonoEmitido();
        $obj->data_atividade = $this->getDataAtividade();
        return $obj;
    }

   

    // CRUD
    
    public function create() {
        $conexao = Banco::getConexao();
        $sql = "INSERT INTO atividades_ecologicas (usuario_id, nome_atividade, quantidade, carbono_emitido, data_atividade) 
                VALUES (?, ?, ?, ?, ?)";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("isdds", $this->usuario_id, $this->nome_atividade, $this->quantidade, $this->carbono_emitido, $this->data_atividade);
        $prepareSql->execute();
        $this->id = $prepareSql->insert_id;
        $prepareSql->close();
        return $this;
    }
public function getTotalCarbono($usuarioId) {
    $conexao = Banco::getConexao();
    $sql = "SELECT 
                SUM(carbono_emitido) AS total,
                SUM(CASE 
                    WHEN YEAR(data_atividade) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
                    AND MONTH(data_atividade) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                    THEN carbono_emitido ELSE 0 
                END) as total_mes_anterior
            FROM atividades_ecologicas
            WHERE usuario_id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $total = $row['total'] ?? 0.0;
    $totalMesAnterior = $row['total_mes_anterior'] ?? 0.0;

    return [
        'total' => (float)$total,
        'comparacao' => ($totalMesAnterior > 0) 
            ? round(($total - $totalMesAnterior) / $totalMesAnterior * 100, 1) 
            : 0
    ];
}



public function getTotalCarbonoMes($usuarioId) {
    $conexao = Banco::getConexao();
    $sql = "SELECT 
                SUM(carbono_emitido) AS total,
                COUNT(DISTINCT DATE(data_atividade)) as dias_ativos,
                AVG(carbono_emitido) as media_diaria
            FROM atividades_ecologicas
            WHERE YEAR(data_atividade) = YEAR(CURRENT_DATE())
              AND MONTH(data_atividade) = MONTH(CURRENT_DATE())
              AND usuario_id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $total = $row && $row['total'] !== null ? (float)$row['total'] : 0.0;
    $diasAtivos = $row && $row['dias_ativos'] !== null ? (int)$row['dias_ativos'] : 0;
    $mediaDiaria = $row && $row['media_diaria'] !== null ? (float)$row['media_diaria'] : 0.0;

    $diasNoMes = date('t');
    $tendencia = $mediaDiaria * $diasNoMes;

    return [
        'total' => $total,
        'tendencia_mes' => $total > 0 ? round(($tendencia - $total) / $total * 100, 1) : 0,
        'dias_ativos' => $diasAtivos
    ];
}





public function getResumoAtividadesPorUsuario($usuarioId) {
    $conexao = Banco::getConexao();
    $sql = "SELECT nome_atividade AS categoria, SUM(quantidade) AS total
            FROM atividades_ecologicas
            WHERE usuario_id = ?
            GROUP BY nome_atividade";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    $atividades = [];
    while ($row = $result->fetch_assoc()) {
        $atividades[] = [
            "category" => $row["categoria"],
            "value"    => (int)$row["total"]
        ];
    }
    $stmt->close();
    return $atividades;
}
private function getDadosPaisAPI($nomePais) {
    try {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true,
                'header' => [
                    'Accept: application/json',
                    'User-Agent: PHP/EcoSystem'
                ]
            ]
        ]);

        // Busca dados de população primeiro
        $urlPopulacao = "https://restcountries.com/v3.1/name/" . urlencode($nomePais) . "?fields=name,population";
        $populacao = @file_get_contents($urlPopulacao, false, $ctx);
        if ($populacao === false) {
            throw new Exception("Erro ao acessar API de população");
        }

        $dadosPopulacao = json_decode($populacao, true);
        if (!$dadosPopulacao || !isset($dadosPopulacao[0]['population'])) {
            throw new Exception("Dados de população inválidos");
        }

        // Busca dados de emissão
        $urlEmissoes = "https://api.climatetrace.org/v6/country/emissions";
        $emissoes = @file_get_contents($urlEmissoes, false, $ctx);
        if ($emissoes === false) {
            throw new Exception("Erro ao acessar API de emissões");
        }

        $dadosEmissoes = json_decode($emissoes, true);
        if (!$dadosEmissoes || !isset($dadosEmissoes['data'])) {
            throw new Exception("Dados de emissão inválidos");
        }

        // Encontra os dados do país nas emissões
        $emissaoPais = null;
        foreach ($dadosEmissoes['data'] as $pais) {
            if (strtolower($pais['country_name']) === strtolower($nomePais)) {
                $emissaoPais = floatval($pais['total_emissions']);
                break;
            }
        }

        if ($emissaoPais === null) {
            throw new Exception("País não encontrado nos dados de emissão");
        }

        // Calcula emissão per capita (converte para kg e divide pela população)
        $populacaoTotal = $dadosPopulacao[0]['population'];
        $emissaoPerCapitaAnual = ($emissaoPais * 1000) / $populacaoTotal; // Converte para kg
        $emissaoPerCapitaDiaria = $emissaoPerCapitaAnual / 365; // Converte para média diária

        return $emissaoPerCapitaDiaria;

    } catch (Exception $e) {
        error_log("Erro ao buscar dados do país {$nomePais}: " . $e->getMessage());
        // Em caso de erro, retorna valor médio estimado baseado em dados históricos
        return match($nomePais) {
            'United States' => 44.79, // ~16.35 toneladas/ano
            'Russian Federation' => 35.61, // ~13 toneladas/ano
            'China' => 21.91, // ~8 toneladas/ano
            'Germany' => 24.65, // ~9 toneladas/ano
            'Argentina' => 13.69, // ~5 toneladas/ano
            default => 20.0
        };
    }
}

public function getComparacaoCarbonoComPaises($usuarioId) {
    $conexao = Banco::getConexao();
    // Calcula média diária do usuário
    $sql = "SELECT SUM(carbono_emitido) as total,
                   COUNT(DISTINCT DATE(data_atividade)) as dias
            FROM atividades_ecologicas 
            WHERE usuario_id = ?
            GROUP BY usuario_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    $mediaUsuario = $row && $row['dias'] > 0 ? $row['total'] / $row['dias'] : 0;
    
    // Lista de países e seus nomes para exibição
    $paises = [
        'United States' => 'EUA',
        'Russian Federation' => 'Rússia',
        'China' => 'China',
        'Germany' => 'Europa',
        'Argentina' => 'Argentina'
    ];

    // Inicia com os dados do usuário
    $dadosComparacao = [
        ["category" => "Você", "valor" => round($mediaUsuario, 2)]
    ];

    // Busca dados de cada país
    foreach ($paises as $nomeAPI => $nomeExibicao) {
        $emissaoPerCapita = $this->getDadosPaisAPI($nomeAPI);
        if ($emissaoPerCapita !== null) {
            $dadosComparacao[] = [
                "category" => $nomeExibicao,
                "valor" => round($emissaoPerCapita, 2)
            ];
        }
    }

    return $dadosComparacao;
}



public function getTotalCarbonoMesPorAno($idUsuario) {
    $conexao = Banco::getConexao();

    $sql = "SELECT 
              MONTH(data_atividade) AS mes_num,
              SUM(carbono_emitido) AS total
            FROM atividades_ecologicas
            WHERE YEAR(data_atividade) = YEAR(CURDATE())
              AND usuario_id = ?
            GROUP BY mes_num
            ORDER BY mes_num";

$stmt = $conexao->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da query: " . $conexao->error);
}
$stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $meses = [
        "Jan", "Fev", "Mar", "Abr", "Mai", "Jun",
        "Jul", "Ago", "Set", "Out", "Nov", "Dez"
    ];

    // Inicializa array com todos os 12 meses zerados
    $dados = array_map(function($nomeMes) {
        return ["mes" => $nomeMes, "carbono" => 0];
    }, $meses);

    // Preenche os dados reais que vierem do banco
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $indice = (int)$row['mes_num'] - 1;
            $dados[$indice]["carbono"] = (float)$row['total'];
        }
    }

    return $dados;
}



  public function getTopDoadores($limite = 5) {
        $conexao = Banco::getConexao();

    $sql = "SELECT u.nome AS usuario, SUM(d.valor) AS total
            FROM doacoes d
            JOIN usuario u ON u.id = d.usuario_id
            GROUP BY d.usuario_id
            ORDER BY total DESC
            LIMIT ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $limite);
    $stmt->execute();
    $res = $stmt->get_result();
    $dados = [];

    while ($row = $res->fetch_assoc()) {
        $dados[] = [
            "category" => $row["usuario"],
            "valor" => (float)$row["total"]
        ];
    }
    return $dados;
}

public function getTopJogos($limite = 5) {
        $conexao = Banco::getConexao();

    $sql = "SELECT u.nome AS usuario, g.resultado, g.data_jogada
            FROM user_game g
            JOIN usuario u ON u.id = g.usuario_id
            ORDER BY g.data_jogada DESC
            LIMIT ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $limite);
    $stmt->execute();
    $res = $stmt->get_result();
    $dados = [];

    while ($row = $res->fetch_assoc()) {
        $dados[] = [
            "category" => $row["usuario"],
            "valor" =>  (float)$row["resultado"]
        ];
    }
    return $dados;
}

public function getTopQuizzes($limite = 5) {
        $conexao = Banco::getConexao();

    $sql = "SELECT u.nome AS usuario, q.quiz_nome, MAX(q.pontuacao) AS maior
            FROM user_quiz q
            JOIN usuario u ON u.id = q.usuario_id
            GROUP BY q.usuario_id, q.quiz_nome
            ORDER BY maior DESC
            LIMIT ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $limite);
    $stmt->execute();
    $res = $stmt->get_result();
    $dados = [];

    while ($row = $res->fetch_assoc()) {
        $dados[] = [
            "category" => $row["usuario"] . " - " . $row["quiz_nome"],
            "valor" => (int)$row["maior"]
        ];
    }
    return $dados;
}

public function getAcertosQuizMes($usuarioId) {
    $usuarioId = 1;
    $conexao = Banco::getConexao();

    // Busca pontuação do mês atual e do mês anterior
    $sql = "SELECT 
            COALESCE(MAX(CASE 
                WHEN YEAR(data_jogada) = YEAR(CURRENT_DATE)
                AND MONTH(data_jogada) = MONTH(CURRENT_DATE)
                THEN pontuacao END), 0) as pontuacao_atual,
            COALESCE(MAX(CASE 
                WHEN YEAR(data_jogada) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
                AND MONTH(data_jogada) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                THEN pontuacao END), 0) as pontuacao_anterior,
            COUNT(DISTINCT CASE 
                WHEN YEAR(data_jogada) = YEAR(CURRENT_DATE)
                AND MONTH(data_jogada) = MONTH(CURRENT_DATE)
                THEN DATE(data_jogada) END) as dias_jogados
            FROM user_quiz 
            WHERE usuario_id = ?";
            
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    $pontuacaoAtual = $resultado['pontuacao_atual'] ?? 0;
    $pontuacaoAnterior = $resultado['pontuacao_anterior'] ?? 0;
    $diasJogados = $resultado['dias_jogados'] ?? 0;

    // Calcula a evolução em relação ao mês anterior
    $evolucao = $pontuacaoAnterior > 0 ? 
        round(($pontuacaoAtual - $pontuacaoAnterior) / $pontuacaoAnterior * 100, 1) : 0;

    return [
        'pontuacao' => $pontuacaoAtual,
        'evolucao' => $evolucao,
        'dias_jogados' => $diasJogados
    ];
}



 public function getTotalDoadoMes($usuarioId) {
    $usuarioId = 1;
    $conexao = Banco::getConexao();

    $sql = "SELECT 
            COALESCE(SUM(CASE 
                WHEN YEAR(data_doacao) = YEAR(CURRENT_DATE)
                AND MONTH(data_doacao) = MONTH(CURRENT_DATE)
                THEN valor END), 0) as total_atual,
            COALESCE(SUM(CASE 
                WHEN YEAR(data_doacao) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
                AND MONTH(data_doacao) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                THEN valor END), 0) as total_anterior,
            COUNT(DISTINCT CASE 
                WHEN YEAR(data_doacao) = YEAR(CURRENT_DATE)
                AND MONTH(data_doacao) = MONTH(CURRENT_DATE)
                THEN DATE(data_doacao) END) as dias_doacao
            FROM doacoes 
            WHERE usuario_id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    $totalAtual = $resultado['total_atual'] ?? 0;
    $totalAnterior = $resultado['total_anterior'] ?? 0;
    $diasDoacao = $resultado['dias_doacao'] ?? 0;

    // Calcula a evolução em relação ao mês anterior
    $evolucao = $totalAnterior > 0 ? 
        round(($totalAtual - $totalAnterior) / $totalAnterior * 100, 1) : 0;

    return [
        'total' => $totalAtual,
        'evolucao' => $evolucao,
        'dias_doacao' => $diasDoacao
    ];
}


    public function readById($id) {
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM atividades_ecologicas WHERE id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("i", $id);
        $prepareSql->execute();
        $result = $prepareSql->get_result()->fetch_object();
        $prepareSql->close();

        if($result) {
            $this->setId($result->id);
            $this->setUsuarioId($result->usuario_id);
            $this->setNomeAtividade($result->nome_atividade);
            $this->setQuantidade($result->quantidade);
            $this->setCarbonoEmitido($result->carbono_emitido);
            $this->setDataAtividade($result->data_atividade);
            return $this;
        }
        return null;
    }

    public function readAll() {
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM atividades_ecologicas ORDER BY data_atividade DESC";
        $result = $conexao->query($sql);

        $atividades = [];
        while($row = $result->fetch_object()) {
            $atividade = new AtividadeEcologica();
            $atividade->setId($row->id);
            $atividade->setUsuarioId($row->usuario_id);
            $atividade->setNomeAtividade($row->nome_atividade);
            $atividade->setQuantidade($row->quantidade);
            $atividade->setCarbonoEmitido($row->carbono_emitido);
            $atividade->setDataAtividade($row->data_atividade);
            $atividades[] = $atividade;
        }
        return $atividades;
    }


     // Getters e setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getUsuarioId() { return $this->usuario_id; }
    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }

    public function getNomeAtividade() { return $this->nome_atividade; }
    public function setNomeAtividade($nome_atividade) { $this->nome_atividade = $nome_atividade; }

    public function getQuantidade() { return $this->quantidade; }
    public function setQuantidade($quantidade) { $this->quantidade = $quantidade; }

    public function getCarbonoEmitido() { return $this->carbono_emitido; }
    public function setCarbonoEmitido($carbono_emitido) { $this->carbono_emitido = $carbono_emitido; }

    public function getDataAtividade() { return $this->data_atividade; }
    public function setDataAtividade($data_atividade) { $this->data_atividade = $data_atividade; }
}
