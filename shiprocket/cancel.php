<?php

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/cancel",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n  \"ids\": [426245951]\n}",
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json",
      "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaXYyLnNoaXByb2NrZXQuaW4vdjEvZXh0ZXJuYWwvYXV0aC9sb2dpbiIsImlhdCI6MTY5ODIzNTgwMywiZXhwIjoxNjk5MDk5ODAzLCJuYmYiOjE2OTgyMzU4MDMsImp0aSI6IlZxQjMwMkQ1Rlk3b3NPQTAiLCJzdWIiOjQwNzczMjAsInBydiI6IjA1YmI2NjBmNjdjYWM3NDVmN2IzZGExZWVmMTk3MTk1YTIxMWU2ZDkifQ.kUMh8T_5w0kMffo-d8h9-BT87w5VkSWVyH7XirQ7wWs"
    ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  echo $response;
?>
