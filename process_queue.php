<?php
require_once 'db.php'; 

echo "Processing queue...\n";

while(true){
    $sql = "SELECT * FROM data_process WHERE process_status = 'queue' ORDER BY queued_at ASC LIMIT 1";
    $stmt = $pdo->query($sql);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if($request){
        $processId = $request['process_id'];
        echo "Processing request: " . $processId . "\n";

        $updateStatusSql = "UPDATE data_process SET process_status = 'processing' WHERE process_id = ?";
        $pdo->prepare($updateStatusSql)->execute([$processId]);

        $data = json_decode($request['request_data']);
        if(isset($data->order) && is_array($data->order)){
            foreach($data->order as $position => $id){
                $userSql = "UPDATE users SET display_order = ? WHERE id = ?";
                $pdo->prepare($userSql)->execute([$position, $id]);
            }
        }

        $updateStatusSql = "UPDATE data_process SET process_status = 'completed' WHERE process_id = ?";
        $pdo->prepare($updateStatusSql)->execute([$processId]);
        echo "Request ID " . $processId . " completed.\n";

    } else {
        echo "No more items in queue..\n";
        sleep(5); 
    }
}
?>
