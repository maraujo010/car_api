<?php

require_once 'CarsApi.class.php';

// Set HTTP_ORIGIN from same server
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $api = new CarsApi($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $api->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}


?>

