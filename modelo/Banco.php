<?php
    class Banco {
        private static $HOST='127.0.0.1';
        private static $USER='root';
        private static $PWD='';
        private static $DB='hackathon';
        private static $PORT=3306;
        private static $CONEXAO = null;

    private static function conectar(){
        try{
            if(Banco::$CONEXAO==null){
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                Banco::$CONEXAO = new mysqli(Banco::$HOST,Banco::$USER,Banco::$PWD,Banco::$DB,Banco::$PORT);
                Banco::$CONEXAO->set_charset('utf8mb4');
                
                if(Banco::$CONEXAO->connect_error){
                    throw new Exception('Erro ao conectar no banco: ' . Banco::$CONEXAO->connect_error);
                }
            }
        } catch (mysqli_sql_exception $e) {
            error_log("Erro SQL: " . $e->getMessage());
            throw new Exception('Erro ao conectar no banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            error_log("Erro geral: " . $e->getMessage());
            throw $e;
        }
    }


    public static function getConexao(){
        if(Banco::$CONEXAO==null){
            Banco::conectar();
        }
        return Banco::$CONEXAO;
    }



}
?>
