<?php

require_once 'db.php';

$json_payload = file_get_contents('php://input');

if(!empty($json_payload)){
    $sql = "INSERT INTO data_process (request_data) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$json_payload]);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Your change has been added to the queue.']);
}else{
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'No data received.']);
}