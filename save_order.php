<?php

require_once 'db.php';

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data->order) && is_array($data->order)) {
    
    $itemOrder = $data->order;

    foreach ($itemOrder as $position => $id) {
        
        $sql = "UPDATE users SET display_order = ? WHERE id = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute([$position, $id]);
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Order saved.']);

} else {

    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received.']);
}
?>