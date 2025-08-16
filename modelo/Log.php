<?php
require_once "modelo/Banco.php";

class Log implements JsonSerializable {
    private $id;
    private $acao;
    private $dataRegistro;
    private $envio;
    private $resposta;

    public function jsonSerialize() {
        $obj = new stdClass();
        $obj->id = $this->getId();
        $obj->acao = $this->getAcao();
        $obj->dataRegistro = $this->getDataRegistro();
        $obj->envio = $this->getEnvio();
        $obj->resposta = $this->getResposta();
        return $obj;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getAcao() {
        return $this->acao;
    }
    public function setAcao($acao) {
        $this->acao = $acao;
    }

    public function getDataRegistro() {
        return $this->dataRegistro;
    }
    public function setDataRegistro($dataRegistro) {
        $this->dataRegistro = $dataRegistro;
    }

    public function getEnvio() {
        return $this->envio;
    }
    public function setEnvio($envio) {
        $this->envio = $envio;
    }

    public function getResposta() {
        return $this->resposta;
    }
    public function setResposta($resposta) {
        $this->resposta = $resposta;
    }

    public function registrarLog($acao, $envio, $resposta) {
        $conexao = Banco::getConexao();
        $sql = "INSERT INTO log (acao, data_registro, envio, resposta) VALUES (?, NOW(), ?, ?)";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("sss", $acao, $envio, $resposta);
        return $prepareSql->execute();
    }

    public function listarTodos() {
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM log ORDER BY data_registro DESC";
        $resultado = $conexao->query($sql);
        $logs = [];
        while ($obj = $resultado->fetch_object(Log::class)) {
            $logs[] = $obj;
        }
        return $logs;
    }

    public function listarPorAcao($acao) {
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM log WHERE acao = ? ORDER BY data_registro DESC";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("s", $acao);
        $prepareSql->execute();
        $resultado = $prepareSql->get_result();
        $logs = [];
        while ($obj = $resultado->fetch_object(Log::class)) {
            $logs[] = $obj;
        }
        return $logs;
    }
}
