<?php
require_once "modelo/Banco.php";
class UserQuiz implements JsonSerializable{
    private $id;
    private $usuario_id;
    private $quiz_nome;
    private $pontuacao; //INT
    private $qtdRespondidas;
    private $qtdCorretas;

    public function jsonSerialize()
    {
        $obj = new stdClass();
        $obj->id = $this->getId();
        $obj->usuario_id = $this->getUsuarioId();
        $obj->quiz_nome = $this->getQuizNome();
        $obj->pontuacao = $this->getPontuacao();
        $obj->qtdRespondidas = $this->getQtdRespondidas();
        $obj->qtdCorretas = $this->getQtdCorretas();
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

    public function getQuizNome(){
        return $this->quiz_nome;
    }

    public function setQuizNome($quiz_nome){
        $this->quiz_nome = $quiz_nome;
    }

    public function getPontuacao(){
        return $this->pontuacao;
    }

    public function setPontuacao($pontuacao){
        $this->pontuacao = $pontuacao;
    }

    public function getQtdRespondidas(){
        return $this->qtdRespondidas;
    }

    public function setQtdRespondidas($qtdRespondidas){
        $this->qtdRespondidas = $qtdRespondidas;
    }

    public function getQtdCorretas(){
        return $this->qtdCorretas;
    }

    public function setQtdCorretas($qtdCorretas){
        $this->qtdCorretas = $qtdCorretas;
    }

    public function readById($usuario_id){
        $conexao = Banco::getConexao();
        $sql = "SELECT usuario_id, quiz_nome, pontuacao, qtdRespondidas, qtdCorretas FROM user_quiz WHERE usuario_id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("i", $usuario_id);
        $executou = $prepareSql->execute();

        $matrizResultados = $prepareSql->get_result();
        $tuplaBanco = $matrizResultados->fetch_object();

        $userQuiz = new UserQuiz();
        $userQuiz->setUsuarioId($tuplaBanco->usuario_id);
        $userQuiz->setQuizNome($tuplaBanco->quiz_nome);
        $userQuiz->setPontuacao($tuplaBanco->pontuacao);
        $userQuiz->setQtdRespondidas($tuplaBanco->qtdRespondidas);
        $userQuiz->setQtdCorretas($tuplaBanco->qtdCorretas);

        $prepareSql->close();
        return $userQuiz;
    }
    
    public function createScore($usuario_id, $quiz_nome, $pontuacao, $qtdRespondidas, $qtdCorretas){
        $conexao = Banco::getConexao();
        $sql = "INSERT INTO user_quiz(usuario_id, quiz_nome, pontuacao, qtdRespondidas, qtdCorretas) VALUES (?, ?, ?, ?, ?);";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("isiii", $usuario_id, $quiz_nome, $pontuacao, $qtdRespondidas, $qtdCorretas);
        $executou = $prepareSql->execute();
        return $executou;
    }
    
    public function updateScore($usuario_id, $pontuacao, $qtdRespondidas, $qtdCorretas){
        $conexao = Banco::getConexao();
        $sql = "UPDATE user_quiz SET pontuacao = ?, qtdRespondidas = ?, qtdCorretas = ? WHERE usuario_id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("iiii", $pontuacao, $qtdRespondidas, $qtdCorretas, $usuario_id);
        $executou = $prepareSql->execute();
        return $executou;
    }
    
    public function isUserQuiz($usuario_id){
        $conexao = Banco::getConexao();
        $sql = "SELECT COUNT(*) AS qtd FROM user_quiz WHERE usuario_id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("i", $usuario_id);
        $executou = $prepareSql->execute();
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();
        return $objTupla->qtd > 0;
    }
}