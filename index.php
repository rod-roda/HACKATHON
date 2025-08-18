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

$roteador->post("/pix/registrar", function(){
    require_once __DIR__ . '/controle/controller_donations.php';
    echo registrarPix();
});

// $roteador->post('/logs/registrar', function () {
//     require_once __DIR__ . '/controle/controller_logs.php';
//     echo registrarLog();
// });

// // Listar todos os logs
// $roteador->get('/logs/listar', function () {
//     require_once __DIR__ . '/controle/controller_logs.php';
//     echo listarLogs();
// });

// // Listar logs filtrando por ação (lê 'acao' do body JSON)
// $roteador->post('/logs/acao', function () {
//     require_once __DIR__ . '/controle/controller_logs.php';
//     echo listarLogsPorAcao();
// });

$roteador->post('/iaServices/pergunta', function () {
    require_once __DIR__ . '/controle/controller_iaServices.php';
    echo responderPerguntaSustentabilidade();
});

$roteador->post('/usuario/token/payload', function () {
    require_once __DIR__ . '/controle/controller_usuarios.php';
    echo readPayloadToken();
});

$roteador->post('/user_game/insert', function () {
    require_once __DIR__ . '/controle/controller_user_game.php';
    echo insertScore();
});

$roteador->post('/user_quiz/insert', function () {
    require_once __DIR__ . '/controle/controller_user_quiz.php';
    echo insertScore();
});

$roteador->get('/user_quiz/read', function () {
    require_once __DIR__ . '/controle/controller_user_quiz.php';
    echo readById();
});

$roteador->get('/user_game/read', function () {
    require_once __DIR__ . '/controle/controller_user_game.php';
    echo readById();
});

$roteador->get('/dashboard/relatorio/dashboards', function () {
    require_once __DIR__ . '/controle/controller_dashboards.php';
    readDashboardStats();
});

$roteador->get('/dashboard/relatorio/graficos', function () {
    require_once __DIR__ . '/controle/controller_dashboards.php';
    readGraficosGerais();
});

$roteador->post('/dashboard', function () {
    require_once __DIR__ . '/controle/controller_dashboards.php';
        echo createAtividade();
});

$roteador->get('/monitoramento/([^/]+)', function ($localizacao) {
    require_once __DIR__ . '/controle/controller_monitoramento.php';
    echo monitoringApiCall($localizacao);
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