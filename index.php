<?php

require __DIR__ . '/vendor/autoload.php';

session_start();
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$route = require __DIR__ . '/config/routes.php';

// $curl = curl_init();

// curl_setopt_array($curl, array(
//     CURLOPT_URL => URL_BASE . 'config/routine.php',
//     CURLOPT_FRESH_CONNECT => true,
//     CURLOPT_TIMEOUT => 1,
// ));

// curl_exec($curl);
// curl_close($curl);