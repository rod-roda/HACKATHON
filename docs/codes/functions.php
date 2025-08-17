<?php
function error($msg, $cod, $resposta = null) {
    if (!$resposta) {
        $resposta = new stdClass();
    }
    $resposta->cod = $cod;
    $resposta->status = false;
    $resposta->msg = $msg;
    return $resposta;
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sanitizarString($str) {
    return htmlspecialchars(strip_tags(trim($str)));
}