<?php

require_once 'db.php';

$queryBase = "SELECT * FROM users WHERE status = 'active' ORDER BY display_order ASC";
$stmt = $pdo->prepare($queryBase);
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
    <script>
        // Mount MultiDrag plugin 
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Sortable && Sortable.MultiDrag && Sortable.mount) {
                try {
                    Sortable.mount(new Sortable.MultiDrag());
                } catch (e) {
         }
            }
        });
    </script>
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
        <div id="reorderNotice" style="font-size:0.8rem; color:#666; margin-bottom:8px;"></div>

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
                    <tr data-id="<?php echo htmlspecialchars($user['id']); ?>" data-order="<?php echo (int)$user['display_order']; ?>">
                        <td class="drag-handle">
                            <i class="fas fa-grip-vertical"></i>
                            <?php echo htmlspecialchars($user['id']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['address']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
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
        $(document).ready(function() {

            const dataTable = $('#userTable').DataTable({
                processing: true,
                scrollX: true,
                ordering: false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All']
                ],
                columnDefs: [{
                    orderable: false,
                    width: '70px',
                    targets: 0
                }]
            });

            const tableBody = document.getElementById('sortable-table');
            let sortableInstance = null;
            let saveTimeout = null;

            function initSortable() {
                if (sortableInstance) {
                    sortableInstance.destroy();
                    sortableInstance = null;
                }
                sortableInstance = new Sortable(tableBody, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    multiDrag: true, 
                    selectedClass: 'row-selected',
                    multiDragKey: 'CTRL', 
                    onEnd: handleDragEnd
                });
            }

            function handleDragEnd() {
                if (saveTimeout) clearTimeout(saveTimeout);
                saveTimeout = setTimeout(savePartial, 400);
            }

            // Toggle selection 
            tableBody.addEventListener('click', function(e) {
                const tr = e.target.closest('tr');
                if (!tr) return;
                if (e.target.closest('.drag-handle')) return; 
                tr.classList.toggle('row-selected');
            });

            function savePartial() {
                const rows = Array.from(tableBody.querySelectorAll('tr'));
                if (rows.length === 0) return;

                const originalOrders = rows.map(r => parseInt(r.getAttribute('data-order'), 10));
                const sortedOriginal = [...originalOrders].sort((a, b) => a - b);
                const updates = rows.map((row, idx) => ({
                    id: parseInt(row.dataset.id, 10),
                    display_order: sortedOriginal[idx]
                }));

                // anything  changed 
                let changed = false;
                for (let i = 0; i < updates.length; i++) {
                    if (parseInt(rows[i].getAttribute('data-order'), 10) !== updates[i].display_order) {
                        changed = true;
                        break;
                    }
                }
                if (!changed) return;

                dataTable.processing(true);
                fetch('save_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            updates
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        dataTable.processing(false);
                        if (data.status === 'success') {
                            // Update data-order
                            updates.forEach((u, idx) => {
                                rows[idx].setAttribute('data-order', u.display_order);
                            });
                            console.log('order saved.');
                        } else {
                            console.warn('Save failed:', data.message);
                        }
                    })
                    .catch(err => {
                        dataTable.processing(false);
                        console.error('Error saving order:', err);
                    });
            }

            dataTable.on('draw.dt', function() {
                initSortable();
            });
            // First init
            initSortable();

        });
    </script>

</body>

</html>