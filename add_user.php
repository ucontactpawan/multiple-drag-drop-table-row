<?php

require_once 'db.php';


header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['users']) || !is_array($data['users']) || count($data['users']) === 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No users provided.']);
    exit;
}


try {
    // Ensure the sequence is ahead of current max(display_order)
    $maxStmt = $pdo->query("SELECT COALESCE(MAX(display_order), -1) AS max_order FROM users");
    $maxOrder = (int)$maxStmt->fetchColumn();
    $autoStmt = $pdo->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'display_order_seq'");
    $seqAuto = (int)$autoStmt->fetchColumn();
    $target = $maxOrder + 1; // next display_order
    if ($seqAuto <= $target) {
        $pdo->exec("ALTER TABLE display_order_seq AUTO_INCREMENT = " . $target);
    }

    $pdo->beginTransaction();
    $sql = "INSERT INTO users (name, address, phone, email, dob, status, gender, position, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert = $pdo->prepare($sql);
    $dupCheck = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1 FOR UPDATE');

    $results = [];
    foreach ($data['users'] as $user) {
        
        $name = trim($user['name'] ?? '');
        $address = trim($user['address'] ?? '');
        $phone = trim($user['phone'] ?? '');
        $email = trim($user['email'] ?? '');
        $dob = trim($user['dob'] ?? '');
        $status = trim($user['status'] ?? '');
        $gender = trim($user['gender'] ?? '');
        $position = trim($user['position'] ?? '');
        if (!$name || !$address || !$phone || !$email || !$dob || !$status || !$gender || !$position) {
            $results[] = [ 'status' => 'error', 'message' => 'Missing required fields', 'user' => $user ];
            continue;
        }
        $dupCheck->execute([$email]);
        if ($dupCheck->fetchColumn()) {
            $results[] = [ 'status' => 'error', 'message' => 'Duplicate email detected. Not inserted again.', 'user' => $user ];
            continue;
        }
        // // Get atomic display_order from sequence table
        // $pdo->exec("INSERT INTO display_order_seq VALUES ()");
        $seqId = $pdo->lastInsertId();
        $insert->execute([$name, $address, $phone, $email, $dob, $status, $gender, $position, $seqId]);
        $results[] = [ 'status' => 'success', 'message' => 'User added', 'user' => $user ];
    }
    $pdo->commit();


    $successCount = count(array_filter($results, fn($r) => $r['status'] === 'success'));
    $errorCount = count($results) - $successCount;
    $msg = $successCount ? "$successCount user(s) added." : "No users added.";
    if ($errorCount) $msg .= " $errorCount error(s).";
    echo json_encode(['status' => $successCount ? 'success' : 'error', 'message' => $msg, 'results' => $results]);
    exit;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    if ($e->getCode() === '23000') {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Duplicate detected (likely same email). Not inserted.']);
        exit;
    }
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
