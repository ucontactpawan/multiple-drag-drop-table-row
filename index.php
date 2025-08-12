<?php

require_once 'db.php';

$sql = "SELECT *  FROM users ORDER BY display_order ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag and Drop Table Rows</title>
    <!-- Font Awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SortableJs Library link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <!-- Custom CSS link -->
    <link rel="stylesheet" href="style.css">
    <!-- bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <!-- jQuery link -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS link -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

</head>

<body>

    <div class="container">
        <h1>Table List</h1>
        <div id="selection-info"></div>

        <table id="userTable" class="custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>Gender</th>
                    <th>Position</th>
                </tr>
            </thead>
            <tbody id="sortable-table">
                <?php
                foreach ($users as $user): ?>
                    <tr data-id="<?php echo htmlspecialchars($user['id']); ?>">
                        <td class="drag-handle">
                            <i class="fas fa-grip-vertical"></i>
                            <?php echo htmlspecialchars($user['id']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['address']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>


                        <!-- ADD THESE NEW CELLS -->
                        <td><?php echo htmlspecialchars($user['dob']); ?></td>
                        <td><?php echo htmlspecialchars($user['status']); ?></td>
                        <td><?php echo htmlspecialchars($user['gender']); ?></td>
                        <td><?php echo htmlspecialchars($user['position']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <script>
        // document.addEventListener('DOMContentLoaded', function(){
        $(document).ready(function() {

            const dataTable = $('#userTable').DataTable({
                "processing": true,
                "scrollX": true,
                "columnDefs": [{
                    "orderable": false,
                    "width": "70px",
                    "targets": 0
                }, ],
                "ordering": false
            });

            const tableBody = document.getElementById('sortable-table');

            // Initialize SortableJs
            new Sortable(tableBody, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                multiDrag: true,
                selectedClass: 'row-selected',

                onEnd: function(evt) {

                    // show processing message
                    dataTable.processing(true);
                    const itemOrder = Array.from(tableBody.querySelectorAll('tr')).map(tr => tr.dataset.id);
                    fetch('save_order.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                order: itemOrder
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            dataTable.processing(false);
                            if (data.status === 'success') {
                                console.log("Order saved successfully.");
                            }
                        })
                        .catch(error => {
                            console.error('Error saving order:', error);
                        });
                }
            });
        });
    </script>

</body>

</html>