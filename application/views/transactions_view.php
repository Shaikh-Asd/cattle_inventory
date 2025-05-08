<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outward Medicines - Cattle Inventory</title>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.2/css/fixedHeader.dataTables.min.css">
    
    <style>
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
            height: auto;
            max-height: 80vh;
        }

        .modal-content {
            height: auto;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            background-color: #fefefe;
            border-radius: 5px;
            box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e5e5;
            flex-shrink: 0;
        }

        .modal-body {
            padding: 12px 15px;
            overflow-y: auto;
            flex: 1;
            max-height: calc(80vh - 120px);
        }

        .modal-footer {
            padding: 12px 15px;
            border-top: 1px solid #e5e5e5;
            flex-shrink: 0;
        }

        .table-responsive {
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 0;
        }

        .btn-group {
            display: flex;
            gap: 5px;
        }

        .dataTables_wrapper {
            position: relative;
            padding: 0;
        }

        .dataTables_scroll {
            clear: both;
        }

        .dataTables_scrollBody {
            overflow-y: auto !important;
        }

        .dataTables_scrollBody thead th[class*="sort"]:after,
        .dataTables_scrollBody thead th[class*="sort"]:before {
            content: "" !important;
        }

        .table>thead>tr>th,
        .table>tbody>tr>td {
            padding: 8px;
            font-size: 13px;
        }

        .medicine-list,
        .quantity-list {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fa-eye {
            cursor: pointer;
            color: #007bff;
        }

        .fa-eye:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Outward Medicines</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Outward Medicines</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="Box">
                        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                            <table class="table table-bordered table-striped" id="transactionsTable">
                                <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Manager</th>
                                        <th>Medicine</th>
                                        <th>Quantity Given</th>
                                        <th>Created</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($transaction->transaction_id); ?></td>
                                            <td>
                                                <a href="<?php echo base_url('MedicineController/customer_transactions/' . $transaction->customer_id); ?>">
                                                    <?php echo htmlspecialchars($transaction->customer_name); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php 
                                                $medicines = explode(',', $transaction->medicine_names);
                                                $quantities = explode(',', $transaction->quantity_given);
                                                $medicinePairs = array_combine($medicines, $quantities);
                                                ksort($medicinePairs);
                                                $totalMedicines = count($medicinePairs);
                                                
                                                if ($totalMedicines > 3) {
                                                    echo '<div class="medicine-list">';
                                                    $count = 0;
                                                    foreach ($medicinePairs as $medicine => $quantity) {
                                                        if ($count >= 3) break;
                                                        echo htmlspecialchars($medicine) . ' (' . $quantity . '),';
                                                        $count++;
                                                    }
                                                    echo '...';
                                                    echo '<i class="fa fa-eye" onclick="showMedicineDetails(' . $transaction->transaction_id . ')" 
                                                         data-medicines="' . htmlspecialchars(json_encode(array_keys($medicinePairs))) . '" 
                                                         data-quantities="' . htmlspecialchars(json_encode(array_values($medicinePairs))) . '"></i>';
                                                    echo '</div>';
                                                } else {
                                                    foreach ($medicinePairs as $medicine => $quantity) {
                                                        echo htmlspecialchars($medicine) . ' (' . $quantity . ')';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($totalMedicines > 3) {
                                                    echo '<div class="quantity-list">';
                                                    $count = 0;
                                                    foreach ($medicinePairs as $quantity) {
                                                        if ($count >= 3) break;
                                                        echo $quantity . ',';
                                                        $count++;
                                                    }
                                                    echo '...';
                                                    echo '<i class="fa fa-eye" onclick="showMedicineDetails(' . $transaction->transaction_id . ')"></i>';
                                                    echo '</div>';
                                                } else {
                                                    foreach ($medicinePairs as $quantity) {
                                                        echo $quantity . ',';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo date('jS M Y h:i A', strtotime($transaction->transaction_date)); ?></td>
                                            <td><?php echo date('jS M Y h:i A', strtotime($transaction->updated_at)); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo base_url('MedicineController/edit_transaction/' . $transaction->transaction_id); ?>" 
                                                       class="btn btn-warning btn-sm">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Transaction Details Modal -->
    <div id="transactionModal" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Outward Medicines Details</h4>
                    <button type="button" class="close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="transactionInfo"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.2.2/js/dataTables.fixedHeader.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#transactionsTable').DataTable({
                responsive: true,
                fixedHeader: true,
                order: [[0, 'desc']],
                pageLength: 10,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });
        });

        function showMedicineDetails(transactionId) {
            const modal = document.getElementById('transactionModal');
            const infoDiv = document.getElementById('transactionInfo');
            const row = event.target.closest('tr');
            const medicines = JSON.parse(row.querySelector('.fa-eye').dataset.medicines);
            const quantities = JSON.parse(row.querySelector('.fa-eye').dataset.quantities);
            
            let html = '<table class="table table-bordered">';
            html += '<thead><tr><th>Medicine</th><th>Quantity</th></tr></thead>';
            html += '<tbody>';
            
            for (let i = 0; i < medicines.length; i++) {
                html += `<tr><td>${medicines[i]}</td><td>${quantities[i]}</td></tr>`;
            }
            
            html += '</tbody></table>';
            infoDiv.innerHTML = html;
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('transactionModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('transactionModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>