<?php

require_once 'db.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['order'])) {
    //  All rows
    if (!is_array($data['order']) || empty($data['order'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid order array']);
        exit;
    }
    foreach ($data['order'] as $rawId) {
        if (!ctype_digit((string)$rawId)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Non-numeric id']);
            exit;
        }
    }
    $existingIds = $pdo->query("SELECT id FROM users ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN);
    $payloadInt = array_map('intval', $data['order']);
    $existingInt = array_map('intval', $existingIds);
    sort($payloadInt); sort($existingInt);
    if ($payloadInt !== $existingInt) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Full reorder must include every row']);
        exit;
    }
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('UPDATE users SET display_order = ? WHERE id = ?');
        foreach ($data['order'] as $position => $id) {
            $stmt->execute([$position + 1, $id]);
        }
        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Full order saved']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    exit;
}

if (isset($data['updates'])) {
    // f {id, display_order}
    if (!is_array($data['updates']) || empty($data['updates'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid updates array']);
        exit;
    }
    // Validate structure
    foreach ($data['updates'] as $row) {
        if (!isset($row['id'], $row['display_order']) || !ctype_digit((string)$row['id']) || !ctype_digit((string)$row['display_order'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Bad update item']);
            exit;
        }
    }
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('UPDATE users SET display_order = ? WHERE id = ?');
        foreach ($data['updates'] as $u) {
            $stmt->execute([$u['display_order'], $u['id']]);
        }
        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Partial order saved']);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    exit;
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'No valid payload']);
?>
