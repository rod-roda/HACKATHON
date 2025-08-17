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
        try {
            $conexao = Banco::getConexao();
            
            // Remove caracteres especiais do CPF
            $cpf = preg_replace('/[^0-9]/', '', $cpf);
            
            $sql = "INSERT INTO usuarios(nome, email, senha_hash, cpf) VALUES (?, ?, ?, ?);";
            $prepareSql = $conexao->prepare($sql);
            if (!$prepareSql) {
                throw new Exception("Erro ao preparar query: " . $conexao->error);
            }
            
            $prepareSql->bind_param("ssss", $nome, $email, $senha_hash, $cpf);
            
            if (!$prepareSql->execute()) {
                $erro = $prepareSql->error;
                $prepareSql->close();
                if (strpos($erro, 'Duplicate entry') !== false) {
                    throw new Exception("Este email já está cadastrado.");
                }
                throw new Exception("Erro ao cadastrar: " . $erro);
            }
            
            $prepareSql->close();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao cadastrar usuário: " . $e->getMessage());
            throw $e;
        }
    }

    public function consultarSenha($email){
        $conexao = Banco::getConexao();
        $sql = "SELECT senha_hash FROM usuarios WHERE email = ?;";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("s", $email);
        $executou = $prepareSql->execute();
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();

        return $objTupla ? $objTupla->senha_hash : null;
    }

    public function logar($email, $senha){
        $conexao = Banco::getConexao();
        $sql = "SELECT count(*) as qtd FROM usuarios WHERE email = ? AND senha_hash = ?;";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("ss", $email, $senha);
        $executou = $prepareSql->execute();
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();
        return $objTupla->qtd > 0;
    }

    public function isUser($email){
        $conexao = Banco::getConexao();
        $sql = "SELECT COUNT(*) AS qtd FROM usuarios WHERE email = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("s", $email);
        $executou = $prepareSql->execute();
        $matrizTuplas = $prepareSql->get_result();
        $objTupla = $matrizTuplas->fetch_object();
        return $objTupla->qtd > 0;
    }

    public function readUserByEmail($email){
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM usuarios WHERE email = ?";
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