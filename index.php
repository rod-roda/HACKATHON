<?php
// Tratamento global de erros para requisições AJAX/API
$isApiRequest = (
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) || (
    isset($_SERVER['CONTENT_TYPE']) && 
    strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
);

if ($isApiRequest) {
    ini_set('display_errors', 0);
    set_exception_handler(function($e) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    });
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $errstr]);
        exit;
    });
}

require_once("modelo/Router.php");

$roteador = new Router();

$roteador->get('/', function () {
    echo 'router OK';
});

$roteador->get('/perguntas/(\d+)', function ($id) {
    require_once __DIR__ . '/HACKATHON/controle/controller_perguntas_quiz.php';
    echo readById($id);
});

$roteador->post('/dashboard', function () {
    require_once __DIR__ . '/controle/controller_dashboards.php';
        echo createAtividade();

});

$roteador->get('/dashboard/relatorio/dashboards', function () {
    require_once __DIR__ . '/controle/controller_dashboards.php';
    readDashboardStats();
});

$roteador->get('/dashboard/relatorio/graficos', function () {
    require_once __DIR__ . '/controle/controller_dashboards.php';
    readGraficosGerais();
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

$roteador->post('/logs/registrar', function () {
    require_once __DIR__ . '/controle/controller_logs.php';
    echo registrarLog();
});

// Listar todos os logs
$roteador->get('/logs/listar', function () {
    require_once __DIR__ . '/controle/controller_logs.php';
    echo listarLogs();
});

// Listar logs filtrando por ação (lê 'acao' do body JSON)
$roteador->post('/logs/acao', function () {
    require_once __DIR__ . '/controle/controller_logs.php';
    echo listarLogsPorAcao();
});

$roteador->post('/iaServices/pergunta', function () {
    require_once __DIR__ . '/controle/controller_iaServices.php';
    echo responderPerguntaSustentabilidade();
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