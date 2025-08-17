<?php
require_once __DIR__ . '/configs.php';

function criptografar($dados) {
    $iv = random_bytes(openssl_cipher_iv_length(CIPHER_METHOD));
    $criptografado = openssl_encrypt($dados, CIPHER_METHOD, ENCRYPTION_KEY, 0, $iv);

    return base64_encode($iv . $criptografado);
}

function descriptografar($dados_criptografados) {
    $dados = base64_decode($dados_criptografados);

    $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
    $iv = substr($dados, 0, $iv_length);
    $criptografado = substr($dados, $iv_length);

    return openssl_decrypt($criptografado, CIPHER_METHOD, ENCRYPTION_KEY, 0, $iv);
}