<?php
require_once "modelo/Banco.php";
class UserGame implements JsonSerializable{
    private $id;
    private $usuario_id;
    private $jogo_nome;
    private $resultado;

    public function jsonSerialize()
    {
        $obj = new stdClass();
        $obj->id = $this->getId();
        $obj->usuario_id = $this->getUsuarioId();
        $obj->jogo_nome = $this->getJogoNome();
        $obj->resultado = $this->getResultado();
        return $obj;
    }
    
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getUsuarioId(){
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id){
        $this->usuario_id = $usuario_id;
    }

    public function getJogoNome(){
        return $this->jogo_nome;
    }

    public function setJogoNome($jogo_nome){
        $this->jogo_nome = $jogo_nome;
    }

    public function getResultado(){
        return $this->resultado;
    }

    public function setResultado($resultado){
        $this->resultado = $resultado;
    }

    public function readById($usuario_id){
        $conexao = Banco::getConexao();
        $sql = "SELECT usuario_id, jogo_nome, resultado FROM user_game WHERE usuario_id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("i", $usuario_id);
        $executou = $prepareSql->execute();

        $matrizResultados = $prepareSql->get_result();
        $tuplaBanco = $matrizResultados->fetch_object();

        $userGame = new UserGame();
        $userGame->setUsuarioId($tuplaBanco->usuario_id);
        $userGame->setJogoNome($tuplaBanco->jogo_nome);
        $userGame->setResultado($tuplaBanco->resultado);

        $prepareSql->close();
        return $userGame;
    }
    
    public function createScore($usuario_id, $jogo_nome, $resultado){
        $conexao = Banco::getConexao();
        $sql = "INSERT INTO user_game(usuario_id, jogo_nome, resultado) VALUES (?, ?, ?);";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("isi", $usuario_id, $jogo_nome, $resultado);
        $executou = $prepareSql->execute();
        return $executou;
    }

    public function updateScore($usuario_id, $resultado){
        $conexao = Banco::getConexao();
        $sql = "UPDATE user_game SET resultado = ? WHERE usuario_id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("ii", $resultado, $usuario_id);
        $executou = $prepareSql->execute();
        return $executou;
    }

    public function isUserGame($usuario_id){
        $conexao = Banco::getConexao();
        $sql = "SELECT COUNT(*) AS qtd FROM user_game WHERE usuario_id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("i", $usuario_id);
        $executou = $prepareSql->execute();
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();
        return $objTupla->qtd > 0;
    }
}