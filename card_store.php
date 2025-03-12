<?php
$data = file_get_contents('php://input');
$data = json_decode($data, true);
$data["id"] = 7;
$response = [
    "status" => true,
    "data" => $data
];
$json = json_encode($response);

header("Content-Type: application/json");
echo $json;
?>