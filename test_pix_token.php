<?php 
  $config = [
    "certificado" => "C:/xampp/htdocs/HACKATON/certificados/certificado_completo.pem",
    "client_id" => "Client_Id_040e1ea39273b7d69e13ba2e1a1fbf363c9d93ca",
    "client_secret" => "Client_Secret_5ebd64768c8002a55f98427382ba212880b6fc9d"
  ];
  $autorizacao =  base64_encode($config["client_id"] . ":" . $config["client_secret"]);

  $curl = curl_init();

  curl_setopt_array($curl, array(
      CURLOPT_URL => "https://pix.api.efipay.com.br/oauth/token",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => '{"grant_type": "client_credentials"}',
      CURLOPT_SSLCERT => $config["certificado"],
      CURLOPT_SSLCERTPASSWD => "", // deixe vazio se a chave já está descriptografada
      CURLOPT_HTTPHEADER => array(
          "Authorization: Basic $autorizacao",
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