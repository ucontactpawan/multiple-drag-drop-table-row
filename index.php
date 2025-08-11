<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag and Drop Table Rows</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SortableJS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        #selection-info {
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.1em;
            color: #3498db;
            font-weight: bold;
            height: 20px; /* Reserve space to prevent layout shift */
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .custom-table th, .custom-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .custom-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
        }

        .custom-table tbody tr {
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
        
        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Style for selected rows */
        .row-selected {
            background-color: #dbeafe !important; /* A light blue to indicate selection */
            color: #1e40af;
        }

        /* Style for the element being dragged */
        .sortable-ghost {
            opacity: 0.4;
            background-color: #cce5ff;
        }
        
        /* Style for the drag handle icon */
        .drag-handle {
            cursor: grab;
            width: 40px; /* Fixed width for the icon column */
            text-align: center;
            color: #999;
        }
        
        .drag-handle:active {
            cursor: grabbing;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Client List</h1>
        <div id="selection-info"></div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 40px;"></th> <!-- Header for drag handle -->
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody id="sortable-table">
                <!-- Sample Data -->
                <tr data-id="1">
                    <td class="drag-handle"><i class="fas fa-grip-vertical"></i></td>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>123 Maple Street</td>
                    <td>555-0101</td>
                </tr>
                <tr data-id="2">
                    <td class="drag-handle"><i class="fas fa-grip-vertical"></i></td>
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>456 Oak Avenue</td>
                    <td>555-0102</td>
                </tr>
                <tr data-id="3">
                    <td class="drag-handle"><i class="fas fa-grip-vertical"></i></td>
                    <td>3</td>
                    <td>Peter Jones</td>
                    <td>789 Pine Lane</td>
                    <td>555-0103</td>
                </tr>
                <tr data-id="4">
                    <td class="drag-handle"><i class="fas fa-grip-vertical"></i></td>
                    <td>4</td>
                    <td>Mary Johnson</td>
                    <td>101 Birch Road</td>
                    <td>555-0104</td>
                </tr>
                <tr data-id="5">
                    <td class="drag-handle"><i class="fas fa-grip-vertical"></i></td>
                    <td>5</td>
                    <td>David Williams</td>
                    <td>212 Cedar Blvd</td>
                    <td>555-0105</td>
                </tr>
                <tr data-id="6">
                    <td class="drag-handle"><i class="fas fa-grip-vertical"></i></td>
                    <td>6</td>
                    <td>Susan Brown</td>
                    <td>333 Elm Street</td>
                    <td>555-0106</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById('sortable-table');
            const selectionInfo = document.getElementById('selection-info');

            // --- 1. Initialize SortableJS ---
            // This library handles the drag-and-drop functionality.
            new Sortable(tableBody, {
                handle: '.drag-handle', // Specify that dragging is initiated by the handle
                animation: 150, // Animation speed in ms
                ghostClass: 'sortable-ghost', // The class for the drop placeholder
                
                // This is the key for multi-drag functionality
                multiDrag: true, 
                selectedClass: 'row-selected', // The class for selected items
                
                // Called when a drag event ends
                onEnd: function (evt) {
                    // You can add an AJAX call here to save the new order to your database
                    console.log('New order saved (simulation).');
                    // Example: Get all row IDs in their new order
                    const itemOrder = Array.from(tableBody.querySelectorAll('tr')).map(tr => tr.dataset.id);
                    console.log(itemOrder);
                }
            });

            // --- 2. Handle Row Selection ---
            // We add a click listener to the table body to handle row selections.
            tableBody.addEventListener('click', function(e) {
                // Find the closest 'tr' element to where the user clicked.
                const clickedRow = e.target.closest('tr');
                if (!clickedRow) return; // Exit if the click was not on a row

                // We don't want to select a row if the user is clicking the drag handle
                if (e.target.closest('.drag-handle')) {
                    return;
                }

                // Toggle the 'row-selected' class on the clicked row.
                // This is handled automatically by SortableJS when multiDrag is true,
                // but we can add custom logic here if needed.
                // For this implementation, SortableJS's default behavior is sufficient.
                
                // Update the selection counter after a brief delay to allow SortableJS to update classes.
                setTimeout(updateSelectionCount, 50);
            });

            // --- 3. Update Selection Counter ---
            // This function updates the text that shows how many rows are selected.
            function updateSelectionCount() {
                const selectedRows = tableBody.querySelectorAll('.row-selected').length;
                if (selectedRows > 0) {
                    selectionInfo.textContent = `${selectedRows} row${selectedRows > 1 ? 's' : ''} selected`;
                } else {
                    selectionInfo.textContent = ''; // Clear text if nothing is selected
                }
            }
            
            // Initial call to set the counter state on page load (nothing selected)
            updateSelectionCount();
        });
    </script>

</body>
</html>