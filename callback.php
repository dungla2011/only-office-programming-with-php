<?php
// http_response_code(200);
$json = file_get_contents("php://input");
$std = json_decode($json);

$fpath = __DIR__."/1.xlsx";
if ($std->status == 2) {
    $url = $std->url;
    $cont = @file_get_contents($url);
    file_put_contents($fpath, $cont);

}

echo json_encode(["error" => 0]);
