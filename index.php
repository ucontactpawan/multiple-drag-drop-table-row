<?php
require_once 'db.php';

$sql = "SELECT * FROM users ORDER BY display_order ASC";
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
</head>

<body>

    <div class="container">
        <h1>Table List</h1>
        <div id="selection-info"></div>

        <table class="custom-table">
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
                    <tr data-id="<?php echo htmlspecialchars($user['id']); ?>">
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
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('sortable-table');
            const selectionInfo = document.getElementById('selection-info');


            // Initialize SortableJs
            new Sortable(tableBody, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                multiDrag: true,
                selectedClass: 'row-selected',

                onEnd: function(evt) {
                    const itemOrder = Array.from(tableBody.querySelectorAll('tr')).map(tr => tr.dataset.id);

                    // Api to send data to server 
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
                            console.log('Saved ', data.status);
                        })
                        .catch(error => {
                            console.error('Error saving order:', error);
                        });
                }
            });

            //Handle row selection
            tableBody.addEventListener('click', function(e) {
                const clickedRow = e.target.closest('tr');
                if (!clickedRow) return;

                if (e.target.closest('.drag-handle')) {
                    return;
                }

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