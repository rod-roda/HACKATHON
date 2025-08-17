<?php
require_once "modelo/Banco.php";

class Donation implements JsonSerializable {
    private $id;
    private $usuarioId;
    private $valor;
    private $dataCriacao;

    public function jsonSerialize() {
        $obj = new stdClass();
        $obj->id = $this->getId();
        $obj->usuarioId = $this->getUsuarioId();
        $obj->valor = $this->getValor();
        $obj->dataCriacao = $this->getDataCriacao();
        return $obj;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsuarioId() {
        return $this->usuarioId;
    }

    public function setUsuarioId($usuarioId) {
        $this->usuarioId = $usuarioId;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    public function cadastrar($usuarioId, $valor) {
        $conexao = Banco::getConexao();
        $sql = "INSERT INTO doacoes(usuario_id, valor, metodo_pagamento, data_doacao) VALUES (?, ?, 'Pix', NOW());";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("id", $usuarioId, $valor);
        return $prepareSql->execute();
    }

    public function listarPorUsuario($usuarioId) {
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM doacoes WHERE usuario_id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("i", $usuarioId);
        $prepareSql->execute();
        $resultado = $prepareSql->get_result();
        $doacoes = [];
        while ($obj = $resultado->fetch_object(Donation::class)) {
            $doacoes[] = $obj;
        }
        return $doacoes;
    }
}
