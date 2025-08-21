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
                } catch (e) {}
            }
        });
    </script>
    <!-- Custom CSS link -->
    <link rel="stylesheet" href="style.css">
    <!-- Modal specific modern style -->
    <link rel="stylesheet" href="modal-style.css">
    <!-- bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <!-- jQuery link -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS link -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <!-- Bootstrap JS bundle for modal functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</head>

<body>



    <div class="container">
        <h1>Table List</h1>
        <div id="selection-info"></div>
        <div id="reorderNotice" style="font-size:0.8rem; color:#666; margin-bottom:8px;"></div>

        <!-- add user button -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Add User
        </button>

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
                    <tr data-id="<?php echo htmlspecialchars($user['id']); ?>"
                        data-order="<?php echo (int) $user['display_order']; ?>">
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

    <!-- Add user modal with dynamic fieldsets -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="addUserForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User(s)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="userFieldsets">
                        <!-- User fieldsets will be appended here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="addMoreBtn">Add More</button>
                        <button type="submit" class="btn btn-success">Save All</button>
                    </div>
                </div>
            </form>
        </div>
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

            // user fieldset
            function getUserFieldset(idx) {
                return `<div class="user-fieldset border rounded p-3 mb-3 bg-white" data-idx="${idx}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">User ${idx + 1}</span>
                        ${idx === 0 ? '' : '<button type="button" class="btn btn-sm btn-danger removeUserBtn" title="Remove"><i class="fas fa-times"></i></button>'}
                    </div>
                    <div class="row g-2 align-items-end flex-nowrap">
                        <div class="col-lg-1 col-md-2 col-6"><input type="text" class="form-control" name="name" placeholder="Name" required></div>
                        <div class="col-lg-1 col-md-2 col-6"><input type="text" class="form-control" name="address" placeholder="Address" required></div>
                        <div class="col-lg-1 col-md-2 col-6"><input type="text" class="form-control" name="phone" placeholder="Phone" required></div>
                        <div class="col-lg-2 col-md-2 col-6"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
                        <div class="col-lg-2 col-md-2 col-6"><input type="date" class="form-control" name="dob" placeholder="Date of Birth" required></div>
                        <div class="col-lg-1 col-md-2 col-6"><select class="form-select" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select></div>
                        <div class="col-lg-1 col-md-2 col-6"><select class="form-select" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select></div>
                        <div class="col-lg-2 col-md-2 col-6"><input type="text" class="form-control" name="position" placeholder="Position" required></div>
                    </div>
                </div>`;
            }

            // Add initial fieldset on modal show
            $('#addUserModal').on('show.bs.modal', function() {
                const $container = $('#userFieldsets');
                $container.empty().append(getUserFieldset(0));
            });

            // Add more fieldsets
            let userCount = 1;
            $('#addMoreBtn').on('click', function() {
                const $container = $('#userFieldsets');
                $container.append(getUserFieldset(userCount));
                userCount++;
            });

            // Remove a fieldset
            $(document).on('click', '.removeUserBtn', function() {
                $(this).closest('.user-fieldset').remove();
                // Renumber remaining fieldsets
                $('#userFieldsets .user-fieldset').each(function(i) {
                    $(this).attr('data-idx', i);
                    $(this).find('span').text('User ' + (i + 1));
                });
                userCount = $('#userFieldsets .user-fieldset').length;
            });

            // Form submission for adding multiple users
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                if ($btn.prop('disabled')) return;
                $btn.prop('disabled', true).text('Saving...');
                // Collect all user fieldsets
                const users = [];
                $('#userFieldsets .user-fieldset').each(function() {
                    const $fs = $(this);
                    const user = {
                        name: $fs.find('input[name="name"]').val().trim(),
                        address: $fs.find('input[name="address"]').val().trim(),
                        phone: $fs.find('input[name="phone"]').val().trim(),
                        email: $fs.find('input[name="email"]').val().trim(),
                        dob: $fs.find('input[name="dob"]').val().trim(),
                        status: $fs.find('select[name="status"]').val(),
                        gender: $fs.find('select[name="gender"]').val(),
                        position: $fs.find('input[name="position"]').val().trim()
                    };
                    users.push(user);
                });
                // Basic validation
                if (users.length === 0 || users.some(u => !u.name || !u.address || !u.phone || !u.email || !u.dob || !u.status || !u.gender || !u.position)) {
                    alert('Please fill all fields for each user.');
                    $btn.prop('disabled', false).text('Save All');
                    return;
                }
                $.ajax({
                    url: 'add_user.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        users
                    }),
                    complete: function(xhr) {
                        let res = null;
                        try {
                            res = xhr.responseJSON || JSON.parse(xhr.responseText);
                        } catch (e) {}
                        let msg = res && res.message ? res.message : (xhr.status === 200 ? 'Users added successfully' : 'Error adding users. Please try again.');
                        //  duplicate email
                        if (res && res.status === 'error' && Array.isArray(res.results)) {
                            const allDupEmail = res.results.length > 0 && res.results.every(r => r.status === 'error' && r.message && r.message.toLowerCase().includes('duplicate email'));
                            if (allDupEmail) {
                                msg = 'Duplicate email. We cannot save.';
                            }
                        }
                        alert(msg);
                        if (xhr.status === 200 && res && res.status === 'success') {
                            const modalEl = document.getElementById('addUserModal');
                            const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                            modalInstance.hide();
                            $form[0].reset();
                            location.reload();
                        }
                        $btn.prop('disabled', false).text('Save All');
                    }
                });
            });


        });
    </script>

</body>

</html>