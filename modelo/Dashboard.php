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
