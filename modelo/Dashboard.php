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
        echo $this->usuario_id;
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("isdds", $this->usuario_id, $this->nome_atividade, $this->quantidade, $this->carbono_emitido, $this->data_atividade);
        $prepareSql->execute();
        $this->id = $prepareSql->insert_id;
        $prepareSql->close();
        return $this;
    }
public function getTotalCarbono() {
    $conexao = Banco::getConexao();
    $sql = "SELECT SUM(carbono_emitido) AS total FROM atividades_ecologicas";
    $result = $conexao->query($sql);
    $row = $result ? $result->fetch_assoc() : null;
    return $row && $row['total'] !== null ? (float)$row['total'] : 0.0;
}

public function getTotalCarbonoMes() {
    $conexao = Banco::getConexao();
    $sql = "SELECT SUM(carbono_emitido) AS total
            FROM atividades_ecologicas
            WHERE YEAR(data_atividade) = YEAR(CURRENT_DATE())
              AND MONTH(data_atividade) = MONTH(CURRENT_DATE())";
    $result = $conexao->query($sql);
    $row = $result ? $result->fetch_assoc() : null;
    return $row && $row['total'] !== null ? (float)$row['total'] : 0.0;
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
public function getComparacaoCarbonoComPaises($usuarioId) {
    $conexao = Banco::getConexao();

    // Pega o último registro de carbono do usuário
    $sqlUltimo = "SELECT carbono_emitido 
                  FROM atividades_ecologicas 
                  WHERE usuario_id = ? 
                  ORDER BY data_atividade DESC 
                  LIMIT 1";
    $stmtUltimo = $conexao->prepare($sqlUltimo);
    $stmtUltimo->bind_param("i", $usuarioId);
    $stmtUltimo->execute();
    $resUltimo = $stmtUltimo->get_result();
    $rowUltimo = $resUltimo->fetch_assoc();
    $stmtUltimo->close();

    $carbonoUsuario = $rowUltimo ? (float)$rowUltimo["carbono_emitido"] : 0;

    // Médias fixas de exemplo para países (em toneladas por pessoa/ano)
    $dadosComparacao = [
        ["category" => "Brasil",     "valor" => 2.2],
        ["category" => "EUA",        "valor" => 15.5],
        ["category" => "China",      "valor" => 8.2],
        ["category" => "Índia",      "valor" => 1.8],
        ["category" => "Você",       "valor" => $carbonoUsuario]
    ];

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
            JOIN usuarios u ON u.id = d.usuario_id
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
            JOIN usuarios u ON u.id = g.usuario_id
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
            JOIN usuarios u ON u.id = q.usuario_id
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

    $mesAtual = date('m');
    $anoAtual = date('Y');

    $sql = "SELECT MAX(pontuacao) as maior_pontuacao
            FROM user_quiz 
            WHERE usuario_id = ? 
           ";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuarioId); // Corrigido aqui
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc(); // Corrigido aqui também

    return $resultado['maior_pontuacao'] ?? 0;
}



 public function getTotalDoadoMes($usuarioId) {
        $usuarioId = 1;

    $conexao = Banco::getConexao();

    $mesAtual = date('m');
    $anoAtual = date('Y');

    $sql = "SELECT SUM(valor) as total 
            FROM doacoes 
            WHERE usuario_id = ? 
             ";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $usuarioId); // Corrigido aqui
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    return $resultado['total'] ?? 0;
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
