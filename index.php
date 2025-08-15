<?php
require_once("modelo/Router.php");

$roteador = new Router();

$roteador->get('/', function () {
    echo 'router OK';
});

$roteador->get('/perguntas/(\d+)', function ($id) {
    require_once __DIR__ . '/controle/controller_perguntas_quiz.php';
    echo readById($id);
});

$roteador->get('/perguntas/random', function () {
    require_once __DIR__ . '/controle/controller_perguntas_quiz.php';
    echo read10Perguntas();
});

$roteador->post("/cadastrar", function(){
    require_once __DIR__ . '/controle/controller_usuarios.php';
    echo cadastrar();
});

$roteador->post("/logar", function(){
    require_once __DIR__ . '/controle/controller_usuarios.php';
    echo logar();
});

$roteador->post('/pix/gerarCodigo', function () {
    require_once __DIR__ . '/controle/controller_donations.php';
    echo postGerarCodigo();
});


/*
EXMPLOS DE ROTAS EM GET,DELETE,POST E PUT

$roteador->get("/usuarios/(\d+)", function($parametro_idusuario){
    require_once("controle/usuario/controle_usuario_read_by_id.php");
});

    $roteador->delete("/usuarios/(\d+)", function($parametro_idusuario){
    require_once("controle/usuario/controle_usuario_delete.php");
});

    $roteador->post("/usuarios", function(){
    require_once("controle/usuario/controle_usuario_create.php");
});


    $roteador->put("/usuarios/(\d+)", function($parametro_idusuario){
    require_once("controle/usuario/controle_usuario_update.php");
});

*/

$roteador->run();

?>