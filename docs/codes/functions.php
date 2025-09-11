<?php
function error($msg, $cod, $resposta = null) {
    if ($resposta === null) {
        $resposta = new stdClass();
    }
    $resposta->cod = $cod;
    $resposta->status = false;
    $resposta->msg = $msg;
    return $resposta;
}