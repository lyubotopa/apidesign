<?php


if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' || strtoupper($_SERVER['REQUEST_METHOD']) == 'PUT') {

    $api = $_COOKIE['api'];
    if(isset($_COOKIE['api']) && $_COOKIE['api'] != null) {
        $file = __DIR__ . '/spec-files/' . $_COOKIE['api'] . '.yaml';
    } else {
        header('HTTP/1.1 400 Unknown API', true, 400);
        error_log("Error saving api. Unknown api.");
        die();
    }

    error_log("Saving api: ".$api . ' in file '.$file);

    $output = fopen($file, "wb");
    $input  = fopen("php://input", "rb");

    if (!$input || !$output) {
        header('HTTP/1.1 500 Internal Server Error.', true, 500);
        error_log("Error saving ".$api);
        die();
    }

    while (!feof($input)) {
        $data = fread($input, 8192);
        $count = fwrite($output, $data);

        if ($count === FALSE) {
             fclose($input);
             fclose($output);
             unlink($outputfile);
             header('HTTP/1.1 500 Internal Server Error', true, 500);
             error_log("Error saving ".$api);
             die();
        }
    }
    fclose($input);
    fclose($output);


} else {



    $loaded = false;
    $api = __DIR__ . '/spec-files/default.yaml';
    if(isset($_COOKIE['api']) && $_COOKIE['api'] != null) {
       $file = __DIR__ . '/spec-files/' . $_COOKIE['api'] . '.yaml';
    }
    
    if (!file_exists($api)) {
       header('HTTP/1.1 404 Not Found', true, 404);
       die();
    }
    if (!is_readable($api)) {
       header('HTTP/1.1 403 Permission Denied', true, 403);
       die();
    }
    if (!is_file($api)) {
       header('HTTP/1.1 404 Not Found', true, 404);
       die();
    }
    $input  = fopen($api, "rb");
    if (!$input) {
       header('HTTP/1.1 500 Internal Server Error', true, 500);
       die();
    }
    header("Content-Type: application/x-yaml");
    
    while (!feof($input)) {
       $data = fread($input, 8192);
       if ($data == FALSE){
           fclose($input);
           die();
       } 
       echo $data;
    }
    fclose($input);
}

?>
