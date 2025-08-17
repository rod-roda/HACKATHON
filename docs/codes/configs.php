<?php
// docs/codes/configs.php
declare(strict_types=1);

// método do cipher (AES-256 usa chave de 32 bytes e IV de 16 bytes no modo CBC)
define('CIPHER_METHOD', 'AES-256-CBC');

// sua chave em Base64 -> decodifica para bytes
define('ENCRYPTION_KEY', base64_decode('4CpHWVK0bhuyGoTplOdrwCErdO55fb0kRNsAYMKN39g='));