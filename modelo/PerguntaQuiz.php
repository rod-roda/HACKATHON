<?php
require_once "modelo/Banco.php";
class PerguntaQuiz implements JsonSerializable{
    private $id_pergunta;
    private $pergunta;
    private $alternativas = [];
    private $alternativa_correta;

    public function jsonSerialize()
    {
        $obj = new stdClass();
        $obj->id_pergunta = $this->getIdPergunta();
        $obj->pergunta = $this->getPergunta();
        $obj->alternativas = $this->getAlternativas();
        $obj->alternativa_correta = $this->getAlternativaCorreta();
        return $obj;
    }

    public function getIdPergunta()
    {
        return $this->id_pergunta;
    }

    public function setIdPergunta($id_pergunta)
    {
        $this->id_pergunta = $id_pergunta;
    }

    public function getPergunta()
    {
        return $this->pergunta;
    }

    public function setPergunta($pergunta)
    {
        $this->pergunta = $pergunta;
    }

    public function getAlternativas()
    {
        return $this->alternativas;
    }

    public function setAlternativas($alternativas)
    {
        $this->alternativas = $alternativas;
    }

    public function getAlternativaCorreta()
    {
        return $this->alternativa_correta;
    }

    public function setAlternativaCorreta($alternativa_correta)
    {
        $this->alternativa_correta = $alternativa_correta;
    }

    public function readById($id){
        $conexao = Banco::getConexao();
        $sql = "SELECT id, pergunta, imagem, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_correta FROM perguntas_quiz WHERE id = ?";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->bind_param("i", $id);
        $executou = $prepareSql->execute();

        $matrizResultados = $prepareSql->get_result();
        $tuplaBanco = $matrizResultados->fetch_object();

        $pergunta = new PerguntaQuiz();
        $pergunta->setIdPergunta($tuplaBanco->id);
        $pergunta->setPergunta($tuplaBanco->pergunta);
        $pergunta->setAlternativas([
            $tuplaBanco->alternativa_a,
            $tuplaBanco->alternativa_b,
            $tuplaBanco->alternativa_c,
            $tuplaBanco->alternativa_d
        ]);
        $pergunta->setAlternativaCorreta($tuplaBanco->alternativa_correta);

        $prepareSql->close();
        return $pergunta;
    }

    public function readAll(){
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM perguntas_quiz ORDER BY id;";
        $prepareSql = $conexao->prepare($sql);
        $prepareSql->execute();

        $matrizResultados = $prepareSql->get_result();
        $perguntas_array = [];
        $i = 0;
        while($tuplaBanco = $matrizResultados->fetch_object()){
            $pergunta = new PerguntaQuiz();
            $pergunta->setIdPergunta($tuplaBanco->id);
            $pergunta->setPergunta($tuplaBanco->pergunta);
            $pergunta->setAlternativas([
                $tuplaBanco->alternativa_a,
                $tuplaBanco->alternativa_b,
                $tuplaBanco->alternativa_c,
                $tuplaBanco->alternativa_d
            ]);
            $pergunta->setAlternativaCorreta($tuplaBanco->alternativa_correta);
            $perguntas_array[$i] = $pergunta;
            $i++;
        }
        $prepareSql->close();
        return $perguntas_array;
    }

    public function read10Perguntas($array_perguntas){
        $conexao = Banco::getConexao();
        $sql = "SELECT * FROM perguntas_quiz WHERE id IN (" . implode(',', array_fill(0, count($array_perguntas), '?')) . ") ORDER BY RAND() LIMIT 10;";
        $prepareSql = $conexao->prepare($sql);
        $types = str_repeat('i', count($array_perguntas));
        $prepareSql->bind_param($types, ...$array_perguntas);
        $prepareSql->execute();

        $matrizResultados = $prepareSql->get_result();
        $perguntas_array = [];
        $i = 0;
        while($tuplaBanco = $matrizResultados->fetch_object()){
            $pergunta = new PerguntaQuiz();
            $pergunta->setIdPergunta($tuplaBanco->id);
            $pergunta->setPergunta($tuplaBanco->pergunta);
            $pergunta->setAlternativas([
                $tuplaBanco->alternativa_a,
                $tuplaBanco->alternativa_b,
                $tuplaBanco->alternativa_c,
                $tuplaBanco->alternativa_d
            ]);
            $pergunta->setAlternativaCorreta($tuplaBanco->alternativa_correta);
            $perguntas_array[$i] = $pergunta;
            $i++;
        }
        $prepareSql->close();
        return $perguntas_array;
    }

}