<?php

function encodedKey($resi): string
{
  $key  = "0ebfffe63d2a481cf57fe7d5ebdc9fd6"; // key fetched from https://deo.shopeemobile.com/shopee/spx-website-live/static/js/19.192f41e8.chunk.js
  $data = [
    'key'  => base64_encode($key), // MGViZmZmZTYzZDJhNDgxY2Y1N2ZlN2Q1ZWJkYzlmZDY=
    'time' => time() // return unix timestamp code
  ];

  $parameter = $resi . "|" . $data['time'] . hash('sha256', ($resi . $data['time'] . $data['key']));

  return $parameter;

}

function shopeeWaybillTrack($waybill): array
{
  $waybill  = strtoupper($waybill);
  $curl     = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://spx.co.id/api/v2/fleet_order/tracking/search?sls_tracking_number=" . encodedKey(resi : $waybill),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER => array(
      "Authority: spx.co.id",
      "Sec-Ch-Ua: \" Not;A Brand\";v=\"99\", \"Google Chrome\";v=\"91\", \"Chromium\";v=\"91\"",
      "Accept: application/json, text/plain, */*",
      "Sec-Ch-Ua-Mobile: ?0",
      "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.106 Safari/537.36",
      "Sec-Fetch-Site: same-origin",
      "Sec-Fetch-Mode: cors",
      "Sec-Fetch-Dest: empty",
      "Referer: https://spx.co.id/detail/$waybill",
      "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
      "Cookie: _ga=GA1.3.1846728554.1660367856; _gid=GA1.3.864556559.1660367856; fms_language=id; _gat_UA-61904553-17=1",
    ),
  ));

  $response = curl_exec($curl);
  $err      = curl_error($curl);

  curl_close($curl);

  return $err ? ['error' => $err] : json_decode($response, true);

}

var_dump(shopeeWaybillTrack('SPXID026007855878'));
