<?php 
  $config = [
    "certificado" => "C:/xampp/htdocs/HACKATON/certificados/certificado_completo.pem",
    "token" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiYWNjZXNzX3Rva2VuIiwiY2xpZW50SWQiOiJDbGllbnRfSWRfMDQwZTFlYTM5MjczYjdkNjllMTNiYTJlMWExZmJmMzYzYzlkOTNjYSIsImFjY291bnQiOjgxNjc0MiwiYWNjb3VudF9jb2RlIjoiZmVjMmVjMzllMTRmMDczNDUzZTdkODZjOGMzNjkzMjkiLCJzY29wZXMiOlsiY29iLnJlYWQiLCJjb2Iud3JpdGUiLCJjb2J2LnJlYWQiLCJjb2J2LndyaXRlIiwiZ24uYmFsYW5jZS5yZWFkIiwiZ24uaW5mcmFjdGlvbnMucmVhZCIsImduLmluZnJhY3Rpb25zLndyaXRlIiwiZ24ua2V5cy5idWNrZXQucmVhZCIsImduLm9wYi5jb25maWcucmVhZCIsImduLm9wYi5jb25maWcud3JpdGUiLCJnbi5vcGIucGFydGljaXBhbnRzLnJlYWQiLCJnbi5vcGIucGF5bWVudC5waXguY2FuY2VsIiwiZ24ub3BiLnBheW1lbnQucGl4LnJlYWQiLCJnbi5vcGIucGF5bWVudC5waXgucmVmdW5kIiwiZ24ub3BiLnBheW1lbnQucGl4LnNlbmQiLCJnbi5waXguZXZwLnJlYWQiLCJnbi5waXguZXZwLndyaXRlIiwiZ24ucGl4LnNhbWVvd25lcnNoaXAuc2VuZCIsImduLnBpeC5zZW5kLnJlYWQiLCJnbi5xcmNvZGVzLnBheSIsImduLnJlY2VpcHRzLnJlYWQiLCJnbi5yZXBvcnRzLnJlYWQiLCJnbi5yZXBvcnRzLndyaXRlIiwiZ24uc2V0dGluZ3MucmVhZCIsImduLnNldHRpbmdzLndyaXRlIiwiZ24uc3BsaXQucmVhZCIsImduLnNwbGl0LndyaXRlIiwibG90ZWNvYnYucmVhZCIsImxvdGVjb2J2LndyaXRlIiwicGF5bG9hZGxvY2F0aW9uLnJlYWQiLCJwYXlsb2FkbG9jYXRpb24ud3JpdGUiLCJwaXgucmVhZCIsInBpeC5zZW5kIiwicGl4LndyaXRlIiwid2ViaG9vay5yZWFkIiwid2ViaG9vay53cml0ZSJdLCJleHBpcmVzSW4iOjM2MDAsImNvbmZpZ3VyYXRpb24iOnsieDV0I1MyNTYiOiJ0VisvcDdIVWw4NXFyWlB4dW9nNUJMdG1paUl3cUpzaFRvc2ZBejZIdjU0PSJ9LCJpYXQiOjE3NTUxMjY1NTAsImV4cCI6MTc1NTEzMDE1MH0.eyWcmHL50u5DkKJRgk1Bn4LUSM5u8qZgu8dEkPiZJeU"
  ];

  $dados = [
    "calendario" => [
      "expiracao" => 3600
    ],
    "devedor" => [
      "cpf" => "12345678909",
      "nome" => "Francisco da Silva"
    ],
    "valor" => [
      "original" => "0.45"
    ],
    "chave" => "23a9924f-1f02-4cab-ab9d-82bf41565451",
    "solicitacaoPagador" => "Cobrança dos serviços prestados."
  ];

  $curl = curl_init();

  curl_setopt_array($curl, array(
      CURLOPT_URL => "https://pix.api.efipay.com.br/v2/cob",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($dados, JSON_UNESCAPED_UNICODE),
      CURLOPT_SSLCERT => $config["certificado"],
      CURLOPT_SSLCERTPASSWD => "", // Se não tiver senha, deixa vazio
      CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer {$config['token']}",
          "Content-Type: application/json"
      ),
  ));

  $response = curl_exec($curl);

  if ($response === false) {
      echo "<pre>Erro CURL: " . curl_error($curl) . "</pre>";
  } else {
      echo "<pre>";
      echo $response;
      echo "</pre>";
  }

  curl_close($curl);
?>
