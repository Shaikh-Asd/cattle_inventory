<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Medicines - Cattle Inventory</title>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.2/css/fixedHeader.dataTables.min.css">
    
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .content-wrapper {
            padding: 20px;
        }

        .box {
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .select2-container {
            width: 100% !important;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 500;
            cursor: pointer;
        }

        .table th:hover {
            background-color: #e9ecef;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-xs {
            padding: 3px 6px;
            font-size: 12px;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .stock-value {
            font-size: 20px;
            margin: 0 10px;
            min-width: 40px;
            display: inline-block;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        .modal-dialog {
            margin: 30px auto;
            width: 60%;
            max-width: 800px;
        }

        .modal-content {
            background-color: #fefefe;
            border-radius: 5px;
            box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e5e5;
        }

        .modal-body {
            padding: 12px 15px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 12px 15px;
            border-top: 1px solid #e5e5e5;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 10px;
            }

            .box {
                padding: 15px;
            }

            .table th,
            .table td {
                padding: 8px;
                font-size: 12px;
            }

            .stock-value {
                font-size: 16px;
            }

            .btn-xs {
                padding: 2px 4px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Manage Medicines</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Manage Medicines</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="box">
                        <div class="form-group col-md-3">
                            <label for="customerSelect">Manager</label>
                            <select id="customerSelect" class="form-control select2">
                                <option value="">Select a user</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?php echo htmlspecialchars($customer->id); ?>">
                                        <?php echo htmlspecialchars($customer->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="table-responsive">
                            <h3>Medicine Summary</h3>
                            <table class="table table-bordered" id="medicineSummaryTable">
                                <thead>
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Medicine Name</th>
                                        <th>Given</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="medicineSummaryTableBody">
                                    <!-- Data will be filled via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Medicine Breakdown Modal -->
    <div id="medicineModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Medicine Breakdown</h4>
                    <button type="button" class="close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="medicineBreakdownTable">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Given</th>
                                <th>Transaction Date</th>
                            </tr>
                        </thead>
                        <tbody id="medicineBreakdownTableBody">
                            <!-- Data will be filled via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.2.2/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                allowClear: true
            });

            // Initialize DataTables
            $('#medicineSummaryTable').DataTable({
                responsive: true,
                fixedHeader: true,
                order: [[0, 'asc']],
                pageLength: 10,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });

            $('#medicineBreakdownTable').DataTable({
                responsive: true,
                fixedHeader: true,
                order: [[2, 'desc']],
                pageLength: 10,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });

            // Handle customer selection change
            $('#customerSelect').on('change', function() {
                fetchCustomerMedicine();
            });
        });

        function fetchCustomerMedicine() {
            const customerId = $("#customerSelect").val();
            if (customerId) {
                $.get("<?php echo base_url('MedicineController/get_customer_medicines/'); ?>" + customerId, function(data) {
                    const medicines = JSON.parse(data);
                    let rows = "";
                    
                    if (medicines && medicines.length > 0) {
                        medicines.forEach(med => {
                            rows += `
                                <tr>
                                    <td>${med.id}</td>
                                    <td>${med.name}</td>
                                    <td>
                                        <button class="btn btn-danger btn-xs" onclick="adjustStock(${med.id}, 'subtract')">-</button>
                                        <span id="stock_${med.id}" class="stock-value">${med.total_given}</span>
                                        <button class="btn btn-success btn-xs" onclick="adjustStock(${med.id}, 'add')">+</button>
                                        <button class="btn btn-warning btn-xs" onclick="updateStock(${med.transaction_id}, ${med.medicine_id}, ${med.total_given > 0 ? 1 : 2})">Update</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-xs" onclick="viewBreakdown(${customerId}, ${med.medicine_id})">View Details</button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        rows = '<tr><td colspan="4">No medicines found for this customer.</td></tr>';
                    }
                    
                    // Destroy existing DataTable instance
                    if ($.fn.DataTable.isDataTable('#medicineSummaryTable')) {
                        $('#medicineSummaryTable').DataTable().destroy();
                    }
                    
                    // Update the table body
                    $("#medicineSummaryTableBody").html(rows);
                    
                    // Reinitialize DataTable
                    $('#medicineSummaryTable').DataTable({
                        responsive: true,
                        fixedHeader: true,
                        order: [[0, 'asc']],
                        pageLength: 10
                    });
                });
            } else {
                // Clear the table when no customer is selected
                if ($.fn.DataTable.isDataTable('#medicineSummaryTable')) {
                    $('#medicineSummaryTable').DataTable().destroy();
                }
                $("#medicineSummaryTableBody").html("");
                $('#medicineSummaryTable').DataTable({
                    responsive: true,
                    fixedHeader: true,
                    order: [[0, 'asc']],
                    pageLength: 10
                });
            }
        }

        function adjustStock(medicineId, operation) {
            const stockElement = document.getElementById("stock_" + medicineId);
            let currentValue = parseInt(stockElement.innerText);

            if (operation === "add") {
                stockElement.innerText = currentValue + 1;
            } else if (operation === "subtract" && currentValue > 0) {
                stockElement.innerText = currentValue - 1;
            }
        }

        function updateStock(transaction_id, medicineId, type) {
            const stockElement = document.getElementById("stock_" + medicineId);
            const updatedStock = parseInt(stockElement.innerText);

            $.post("<?php echo base_url('MedicineController/update_stock'); ?>", {
                transaction_id: transaction_id,
                medicine_id: medicineId,
                quantity_given: updatedStock,
                type: type
            }, function(response) {
                Swal.fire({
                    title: 'Success',
                    text: 'Stock updated successfully!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    fetchCustomerMedicine();
                });
            }, "json");
        }

        function viewBreakdown(customerId, medicineId) {
            console.log('Opening modal for customer:', customerId, 'medicine:', medicineId);
            
            // Show the modal first
            const modal = document.getElementById("medicineModal");
            modal.style.display = "block";
            
            // Clear existing data
            $("#medicineBreakdownTableBody").empty();
            
            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#medicineBreakdownTable')) {
                $('#medicineBreakdownTable').DataTable().destroy();
            }
            
            $.get("<?php echo base_url('MedicineController/get_medicine_breakdown/'); ?>" + customerId + "/" + medicineId, function(data) {
                console.log('Received data:', data);
                try {
                    let rows = "";
                    
                    if (data && data.length > 0) {
                        data.forEach(med => {
                            // Format the date
                            const date = new Date(med.transaction_date);
                            const formattedDate = date.toLocaleString('en-US', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            
                            rows += `
                                <tr>
                                    <td>${med.name || 'N/A'}</td>
                                    <td>${med.quantity_given || 0}</td>
                                    <td>${formattedDate}</td>
                                </tr>
                            `;
                        });
                    } else {
                        rows = '<tr><td colspan="3">No transaction history found.</td></tr>';
                    }
                    
                    // Update the table body
                    $("#medicineBreakdownTableBody").html(rows);
                    
                    // Initialize DataTable
                    $('#medicineBreakdownTable').DataTable({
                        responsive: true,
                        fixedHeader: true,
                        order: [[2, 'desc']],
                        pageLength: 10,
                        language: {
                            search: "Search:",
                            lengthMenu: "Show _MENU_ entries",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            infoEmpty: "Showing 0 to 0 of 0 entries",
                            infoFiltered: "(filtered from _MAX_ total entries)"
                        },
                        destroy: true // Allow reinitialization
                    });
                    
                } catch (error) {
                    console.error('Error processing data:', error);
                    $("#medicineBreakdownTableBody").html('<tr><td colspan="3">Error loading data. Please try again.</td></tr>');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching medicine breakdown:', textStatus, errorThrown);
                $("#medicineBreakdownTableBody").html('<tr><td colspan="3">Error loading data. Please try again.</td></tr>');
            });
        }

        function closeModal() {
            document.getElementById("medicineModal").style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById("medicineModal");
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>