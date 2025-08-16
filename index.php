<?php
require_once("modelo/Router.php");

$roteador = new Router();

$roteador->get('/', function () {
    echo 'router OK';
});

$roteador->get('/perguntas/(\d+)', function ($id) {
    require_once __DIR__ . '/HACKATON/controle/controller_perguntas_quiz.php';
    echo readById($id);
});

$roteador->post('/dashboard', function () {
    require_once __DIR__ . '/controle/controller_dashboards.php';
        echo createAtividade();

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