<?php 

require_once 'db.php';

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json);

if(isset($data->order) && is_array($data->order)){

    $pdo->beginTransaction();

    try{
        foreach($data->order as $position => $id){
            $sql = "UPDATE users SET display_order  = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$position, $id]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Saved successfully']);
    }catch(PDOException $e){
        $pdo-> rollBack();

        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'failed to save order: ' . $e->getMessage()]);
    }
}else{
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid  data']);
}
?>
