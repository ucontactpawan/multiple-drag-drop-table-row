<?php
require_once 'db.php';

$sql = "SELECT id, name, address, phone, email, last_update FROM users ORDER BY display_order ASC";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag and Drop Table Rows</title>
    <!-- Font Awesome for drag handle icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Sortable.js for drag-and-drop functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <!-- Custom CSS for styling -->
    <link rel="stylesheet" href="style.css">
    <!-- DataTables CSS for styling -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <!-- jQuery for easier DOM manipulation -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS for add features like pagination, searching -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

</head>

<body>

    <div class="container">
        <h1>Table List</h1>
        <div id="selection-info"></div>
        <div id="update-message" style="text-align: center; color: #28a745; font-weight: bold; height: 20px; margin-bottom: 10px;"></div>

        <table id="userTable" class="custom-table" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 40px;"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody id="sortable-table">
                <?php
                foreach ($users as $user): ?>
                    <!-- Add a 'data-last-update' attribute to store the timestamp -->
                    <tr data-id="<?php echo htmlspecialchars($user['id']); ?>"
                        data-last-update="<?php echo htmlspecialchars($user['last_update']); ?>">
                        <td class="drag-handle"><i class="fas fa-grip-vertical"></i></td>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['address']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <script>
        $(document).ready(function() {

            //Initialize table
            const dataTable = $('#userTable').dataTable({
                //Diable for first column
                "columnDefs": [{
                    "orderable": false,
                    "target": 0
                }],
                "ordering": false,
            });

            //Initialize Sortable.js on table body
            const tableBody = document.getElementById('sortable-table');
            const updateMessageDiv = document.getElementById('update-message');

            new Sortable(tableBody, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {

                    const itemOrder = Array.from(tableBody.querySelectorAll('tr')).map(tr => tr.dataset.id);
                    const firstRow = tableBody.querySelector('tr');
                    const lastUpdateTimestamp = firstRow ? firstRow.dataset.lastUpdate : null;

                    fetch('save_order.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                order: itemOrder,
                                last_update: lastUpdateTimestamp
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Server response:', data.message);

                            if (data.status === 'success') {
                                updateMessageDiv.style.color = '#28a745';
                                updateMessageDiv.textContent = 'Order updated successfully!';
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                updateMessageDiv.style.color = 'red';
                                updateMessageDiv.textContent = data.message;
                            }
                        })
                        .catch(error => {
                            console.error('Error saving order:', error);
                            updateMessageDiv.style.color = 'red';
                            updateMessageDiv.textContent = 'An error occurred while saving.';
                        });
                }
            });

            const selectionInfo = document.getElementById('selection-info');
            tableBody.addEventListener('click', function(e) {
                const clickedRow = e.target.closest('tr');
                if (!clickedRow || e.target.closest('.drag-handle')) return;
                setTimeout(updateSelectionCount, 50);
            });

            function updateSelectionCount() {
                const selectedRows = tableBody.querySelectorAll('.row-selected').length;
                if (selectedRows > 0) {
                    selectionInfo.textContent = `${selectedRows} row${selectedRows > 1 ? 's' : '' } selected`;
                } else {
                    selectionInfo.textContent = '';
                }
            }
            updateSelectionCount();
        });
    </script>


</body>

</html>