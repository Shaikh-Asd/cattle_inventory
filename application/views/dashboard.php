<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cattle Inventory</title>
    <style>
        .negative-stock { color: red; }
        .low-stock { color: orange; }
        .in-stock { color: green; }

        @media (max-width: 767px) {
            .dropdown-menu {
                position: static;
                float: none;
                width: auto;
                margin-top: 0;
                background-color: transparent;
                border: 0;
                box-shadow: none;
            }
            
            .dropdown-menu > li > a {
                padding: 5px 15px 5px 25px;
            }
            
            .dropdown-menu > li > a:hover,
            .dropdown-menu > li > a:focus {
                background-color: rgba(0,0,0,0.1);
            }
            
            .dropdown.active > .dropdown-menu {
                display: block;
            }

            .treeview-menu {
                position: static;
                float: none;
                width: auto;
                margin-top: 0;
                background-color: #2c3b41;
                border: 0;
                box-shadow: none;
                padding-left: 20px;
            }
            
            .treeview-menu > li > a {
                padding: 5px 15px 5px 25px;
                color: #8aa4af;
            }
            
            .treeview-menu > li > a:hover,
            .treeview-menu > li > a:focus {
                background-color: #2c3b41;
                color: #fff;
            }
            
            .treeview.active > .treeview-menu {
                display: block;
            }

            .sidebar-menu > li > a {
                padding: 12px 5px 12px 15px;
            }

            .sidebar-menu > li > a > .fa,
            .sidebar-menu > li > a > .glyphicon,
            .sidebar-menu > li > a > .ion {
                width: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <h1>Dashboard</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main Content -->
        <section class="content">
            <?php if ($is_admin == true): ?>
                <!-- Statistics Boxes -->
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo count($total_customers) ?></h3>
                                <h4><b>Total Manager</b></h4>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="<?php echo base_url('Controller_Customer/') ?>" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo count($total_vendors) ?></h3>
                                <h4><b>Total Vendors</b></h4>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="<?php echo base_url('Controller_Customer/') ?>" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div> -->

                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo count($total_medicines) ?></h3>
                                <h4><b>Total Medicines</b></h4>
                            </div>
                            <div class="icon">
                                <i class="fa fa-medkit"></i>
                            </div>
                            <a href="<?php echo base_url('Controller_Medicines/') ?>" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?php echo $total_products ?></h3>
                                <h4><b>Total Inward</b></h4>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cube"></i>
                            </div>
                            <a href="<?php echo base_url('Controller_Products/') ?>" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?php echo $total_orders ?></h3>
                                <h4><b>Total Outward</b></h4>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cart-arrow-down"></i>
                            </div>
                            <a href="<?php echo base_url('MedicineController/view_transactions') ?>" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Medicines Stock Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-lg-12">
                                    <div class="col-lg-6">
                                        <h3>Medicines Stock</h3>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchTable()" class="form-control mb-2">
                                    </div>
                                    <div class="col-lg-2">
                                        <label>Rows per page:</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <select id="rowsPerPage" onchange="changeRowsPerPage()" class="form-control mb-2" style="width: 100px;">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                    <table id="medicineStockTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th onclick="sortTable(0)">Sr No. ▲</th>
                                                <th onclick="sortTable(1)">Medicine ▲</th>
                                                <th onclick="sortTable(2)">Stock Quantity ▲</th>
                                                <th onclick="sortTable(3)">Status ▲</th>
                                            </tr>
                                        </thead>
                                        <tbody id="medicineStockTableBody">
                                            <?php foreach ($medicines as $index => $medicine): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?php echo htmlspecialchars($medicine['name']); ?></td>
                                                    <td><?php echo $medicine['qty']; ?></td>
                                                    <td class="<?php 
                                                        echo $medicine['qty'] < 0 ? 'negative-stock' : 
                                                            ($medicine['qty'] < 10 ? 'low-stock' : 'in-stock'); 
                                                    ?>">
                                                        <?php 
                                                        echo $medicine['qty'] < 0 ? 'Out of Stock' : 
                                                            ($medicine['qty'] < 10 ? 'Low Stock' : 'In Stock'); 
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <div id="pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-lg-12">
                                    <h2>Manager Current Stock</h2>
                                    <div class="col-lg-3 col-xs-6">
                                        <label for="customerSelect">Select Manager:</label>
                                        <select id="customerSelect" class="form-control" onchange="fetchCustomerMedicine()">
                                            <option value="">-- Select manager --</option>
                                            <?php foreach ($total_customers as $customer): ?>
                                                <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 mt-2" style="margin-top: 5px;">
                                        <div class="table-responsive">
                                            <table id="medicineSummary" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Medicine</th>
                                                        <th>Quantity</th>
                                                        <th>Status</th>
                                                        <th>Last Updated</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="medicineSummaryBody">
                                                    <tr>
                                                        <td colspan="5" class="text-center">Please select a manager to view their current stock</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-lg-12">
                                    <h2>Manager Wise Data</h2>
                                    <div class="col-lg-3 col-xs-6">
                                        <label for="userSelect">Select Manager:</label>
                                        <select id="userSelect" class="form-control" onchange="fetchUserMedicineStats()">
                                            <option value="">-- Select manager --</option>
                                            <?php foreach ($total_customers as $customer): ?>
                                                <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 mt-2" style="margin-top: 5px;">
                                        <div class="table-responsive">
                                            <table id="userMedicineStatsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Medicine</th>
                                                        <th>Quantity</th>
                                                        <th>Transaction Type</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="userMedicineStatsTableBody">
                                                    <tr>
                                                        <td colspan="5" class="text-center">Please select a manager to view their transaction history</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <script>
        // Table Search Function
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('medicineStockTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let found = false;
                const td = tr[i].getElementsByTagName('td');
                
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? '' : 'none';
            }
        }

        // Table Sorting Function
        function sortTable(n) {
            const table = document.getElementById('medicineStockTable');
            const rows = table.rows;
            const switching = true;
            let shouldSwitch = false;
            let i, x, y;

            while (switching) {
                switching = false;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName('td')[n];
                    y = rows[i + 1].getElementsByTagName('td')[n];

                    if (x && y) {
                        const xValue = isNaN(x.innerHTML) ? x.innerHTML.toLowerCase() : parseFloat(x.innerHTML);
                        const yValue = isNaN(y.innerHTML) ? y.innerHTML.toLowerCase() : parseFloat(y.innerHTML);

                        if (xValue > yValue) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }

        // Pagination Function
        function changeRowsPerPage() {
            const select = document.getElementById('rowsPerPage');
            const rowsPerPage = parseInt(select.value);
            const table = document.getElementById('medicineStockTable');
            const rows = table.getElementsByTagName('tr');
            const totalPages = Math.ceil((rows.length - 1) / rowsPerPage);
            let currentPage = 1;

            function showPage(page) {
                for (let i = 1; i < rows.length; i++) {
                    rows[i].style.display = 'none';
                }

                const start = (page - 1) * rowsPerPage + 1;
                const end = Math.min(start + rowsPerPage - 1, rows.length - 1);

                for (let i = start; i <= end; i++) {
                    rows[i].style.display = '';
                }
            }

            function updatePagination() {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.className = 'btn btn-default';
                    if (i === currentPage) {
                        button.className += ' active';
                    }
                    button.onclick = function() {
                        currentPage = i;
                        showPage(currentPage);
                        updatePagination();
                    };
                    pagination.appendChild(button);
                }
            }

            showPage(currentPage);
            updatePagination();
        }

        // Initialize pagination
        document.addEventListener('DOMContentLoaded', function() {
            changeRowsPerPage();
        });

        function fetchCustomerMedicine() {
            var customerId = $("#customerSelect").val();
            if (customerId) {
                $.ajax({
                    url: "<?php echo base_url('MedicineController/get_customer_medicines/'); ?>" + customerId,
                    method: "GET",
                    success: function(data) {
                        try {
                            var response = typeof data === 'string' ? JSON.parse(data) : data;
                            var output = '';
                            var i = 1;

                            if (response && (Array.isArray(response) || typeof response === 'object')) {
                                var dataArray = Array.isArray(response) ? response : [response];
                                
                                if (dataArray.length > 0) {
                                    dataArray.forEach(function(med) {
                                        var statusClass = '';
                                        var statusText = '';
                                        
                                        if (med.total_given <= 0) {
                                            statusClass = 'negative-stock';
                                            statusText = 'Out of Stock';
                                        } else if (med.total_given <= 10) {
                                            statusClass = 'low-stock';
                                            statusText = 'Low Stock';
                                        } else {
                                            statusClass = 'in-stock';
                                            statusText = 'In Stock';
                                        }

                                        output += '<tr>';
                                        output += '<td>' + i + '</td>';
                                        output += '<td>' + (med.name || 'N/A') + '</td>';
                                        output += '<td>' + (med.total_given || '0') + '</td>';
                                        output += '<td class="' + statusClass + '">' + statusText + '</td>';
                                        output += '<td>' + (med.last_updated ? new Date(med.last_updated).toLocaleString() : 'N/A') + '</td>';
                                        output += '</tr>';
                                        i++;
                                    });
                                } else {
                                    output = '<tr><td colspan="5" class="text-center">No medicines found for this manager.</td></tr>';
                                }
                            } else {
                                output = '<tr><td colspan="5" class="text-center">Invalid data format received from server.</td></tr>';
                            }
                            
                            $('#medicineSummaryBody').html(output);
                            
                            // Initialize or reinitialize DataTable
                            try {
                                var table = $('#medicineSummary');
                                if ($.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
                                    table.DataTable().destroy();
                                }
                                table.DataTable({
                                    "paging": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "lengthChange": true,
                                    "pageLength": 10,
                                    "responsive": true
                                });
                            } catch (dtError) {
                                console.error('DataTable initialization error:', dtError);
                                table.addClass('table table-bordered table-striped');
                            }
                        } catch (e) {
                            console.error('Error processing response:', e);
                            $('#medicineSummaryBody').html('<tr><td colspan="5" class="text-center">Error processing data. Please try again.</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        $('#medicineSummaryBody').html('<tr><td colspan="5" class="text-center">Error loading data. Please try again.</td></tr>');
                    }
                });
            } else {
                $('#medicineSummaryBody').html('<tr><td colspan="5" class="text-center">Please select a manager.</td></tr>');
            }
        }

        function fetchUserMedicineStats() {
            var userId = $("#userSelect").val();
            if (userId) {
                $.ajax({
                    url: "<?php echo base_url('dashboard/getUserMedicineStats/'); ?>" + userId,
                    method: "GET",
                    success: function(data) {
                        try {
                            var response = typeof data === 'string' ? JSON.parse(data) : data;
                            var output = '';
                            var i = 1;

                            if (response && (Array.isArray(response) || typeof response === 'object')) {
                                var dataArray = Array.isArray(response) ? response : [response];
                                
                                if (dataArray.length > 0) {
                                    dataArray.forEach(function(row) {
                                        var date = row.created_at ? new Date(row.created_at) : new Date();
                                        var formattedDate = date.toLocaleString('en-GB', {
                                            day: '2-digit',
                                            month: 'long',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit',
                                            hour12: true
                                        });

                                        output += '<tr>';
                                        output += '<td>' + i + '</td>';
                                        output += '<td>' + (row.medicine_name || 'N/A') + '</td>';
                                        output += '<td>' + (row.total_quantity_ordered || '0') + '</td>';
                                        output += '<td>' + formattedDate + '</td>';
                                        output += '</tr>';
                                        i++;
                                    });
                                } else {
                                    output = '<tr><td colspan="4" class="text-center">No transaction history found for this manager.</td></tr>';
                                }
                            } else {
                                output = '<tr><td colspan="4" class="text-center">Invalid data format received from server.</td></tr>';
                            }
                            
                            $('#userMedicineStatsTableBody').html(output);
                            
                            // Initialize or reinitialize DataTable
                            try {
                                var table = $('#userMedicineStatsTable');
                                if ($.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
                                    table.DataTable().destroy();
                                }
                                table.DataTable({
                                    "paging": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "lengthChange": true,
                                    "pageLength": 10,
                                    "responsive": true
                                });
                            } catch (dtError) {
                                console.error('DataTable initialization error:', dtError);
                                table.addClass('table table-bordered table-striped');
                            }
                        } catch (e) {
                            console.error('Error processing response:', e);
                            $('#userMedicineStatsTableBody').html('<tr><td colspan="4" class="text-center">Error processing data. Please try again.</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        $('#userMedicineStatsTableBody').html('<tr><td colspan="4" class="text-center">Error loading data. Please try again.</td></tr>');
                    }
                });
            } else {
                $('#userMedicineStatsTableBody').html('<tr><td colspan="4" class="text-center">Please select a manager.</td></tr>');
            }
        }

        // Initialize DataTables for all tables
        function initializeDataTables() {
            try {
                if ($.fn.DataTable) {
                    $('.table').each(function() {
                        if (!$.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable({
                                "paging": true,
                                "searching": true,
                                "ordering": true,
                                "info": true,
                                "lengthChange": true,
                                "pageLength": 10,
                                "responsive": true
                            });
                        }
                    });
                }
            } catch (e) {
                console.error('Error initializing DataTables:', e);
            }
        }

        // Call initialization after page load
        initializeDataTables();
    </script>
</body>
</html>