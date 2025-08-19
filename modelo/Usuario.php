<?php
require_once "modelo/Banco.php";
class Usuario implements JsonSerializable{
    private $id;
    private $nome;
    private $email;
    private $cpf;

    public function jsonSerialize()
    {
        $obj = new stdClass();
        $obj->id = $this->getId();
        $obj->nome = $this->getNome();
        $obj->email = $this->getEmail();
        $obj->cpf = $this->getCpf();
        return $obj;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    public function cadastrar($nome, $email, $senha_hash, $cpf)
    {
        $conexao = Banco::getConexao();
        $sql = "INSERT INTO usuario(nome, email, senha_hash, cpf) VALUES (?, ?, ?, ?);";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("ssss", $nome, $email, $senha_hash, $cpf);
        $executou = $prepareSql->execute();
        return $executou;
    }

    public function consultarSenha($email){
        $conexao = Banco::getConexao();
        $sql = "SELECT senha_hash FROM usuario WHERE email = ?;";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("s", $email);
        $executou = $prepareSql->execute();
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();

        return $objTupla ? $objTupla->senha_hash : null;
    }

    public function logar($email, $senha){
        $conexao = Banco::getConexao();
        $sql = "SELECT count(*) as qtd FROM usuario WHERE email = ? AND senha_hash = ?;";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("ss", $email, $senha);
        $executou = $prepareSql->execute();
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();
        return $objTupla->qtd > 0;
    }

    public function isUser($email){
        $conexao = Banco::getConexao();
        $sql = "SELECT COUNT(*) AS qtd FROM usuario WHERE email = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("s", $email);
        $executou = $prepareSql->execute();
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();
        return $objTupla->qtd > 0;
    }

    public function readUserByEmail($email){
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("s", $email);
        $executou = $prepareSql->execute();
        
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();
        
        $usuario = new Usuario();
        $usuario->setId($objTupla->id);
        $usuario->setNome($objTupla->nome);
        $usuario->setCpf($objTupla->cpf);

        $prepareSql->close();
        return $usuario;
    }
}