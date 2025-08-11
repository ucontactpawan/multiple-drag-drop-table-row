<?php
require_once 'db.php';

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data->order) && is_array($data->order) && isset($data->last_update)) {
    
    $clientTimestamp = $data->last_update;
    $sql_check = "SELECT MAX(last_update) as latest_update FROM users";
    $stmt_check = $pdo->query($sql_check);
    $dbTimestamp = $stmt_check->fetchColumn();


    if ($clientTimestamp != $dbTimestamp) {
        header('Content-Type: application/json');
        http_response_code(409);
        echo json_encode([
            'status' => 'conflict', 
            'message' => 'Error: The list was updated by someone else. Please refresh and try again.'
        ]);
        exit(); 
    }

    $pdo->beginTransaction();
    try {
        foreach ($data->order as $position => $id) {
            $sql = "UPDATE users SET display_order = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$position, $id]);
        }
        $pdo->commit();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Order saved successfully.']);

    } catch (Exception $e) {
        $pdo->rollBack();
        header('Content-Type: application/json');
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Failed to save order: ' . $e->getMessage()]);
    }

} else {
    header('Content-Type: application/json');
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received.']);
}
?>
