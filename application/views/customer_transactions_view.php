<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    .content-wrapper {
        padding: 20px;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-title {
        margin: 0;
        color: #2c3e50;
        font-size: 1.8rem;
    }

    .back-btn {
        padding: 8px 15px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
    }

    .back-btn i {
        font-size: 0.85rem;
    }

    .back-btn:hover {
        transform: translateX(-3px);
    }

    .box-primary {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        margin-bottom: 20px;
        padding: 20px;
    }

    .filter-section {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-right: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-control {
        height: 38px;
        border-radius: 4px;
    }

    .btn {
        height: 38px;
        padding: 0 15px;
        border-radius: 4px;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 6px 12px;
        margin-left: 5px;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 6px 12px;
    }

    .empty-message {
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Outward Transactions</h1>
        <div class="header-section">
            <h1 class="page-title">History for Manager: <b><?= $customer->name; ?></b></h1>
            <a href="javascript:void(0);" onclick="goBack()" class="btn btn-warning back-btn">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="box-primary">
                    <!-- Date Filter -->
                    <div class="filter-section">
                        <form id="filterForm" class="form-inline">
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                            </div>
                            <div class="form-group" style="margin-top: 24px;">
                                <button type="button" class="btn btn-primary" id="filterBtn">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <button type="button" class="btn btn-secondary" id="resetBtn">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="transactionsTable">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Quantity Given</th>
                                    <th>Transaction Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($transactions)): ?>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?= $transaction->medicine_name; ?></td>
                                            <td><?= $transaction->quantity_given; ?></td>
                                            <td><?= date('Y-m-d H:i:s', strtotime($transaction->created_at)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="empty-message">No history found for this period.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Required JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#transactionsTable').DataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 10,
            "order": [
                [2, "desc"]
            ], // Sort by transaction date by default
            "lengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "language": {
                "lengthMenu": "Show _MENU_ entries",
                "zeroRecords": "No matching records found",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "search": "Search:",
                "paginate": {
                    "first": '<i class="fas fa-angle-double-left"></i>',
                    "previous": '<i class="fas fa-angle-left"></i>',
                    "next": '<i class="fas fa-angle-right"></i>',
                    "last": '<i class="fas fa-angle-double-right"></i>'
                }
            }
        });

        // Custom date range filter
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var transactionDate = moment(data[2]).format('YYYY-MM-DD');

                if (!startDate && !endDate) return true;

                if (!startDate) return moment(transactionDate).isSameOrBefore(endDate);
                if (!endDate) return moment(transactionDate).isSameOrAfter(startDate);

                return moment(transactionDate).isBetween(startDate, endDate, 'day', '[]');
            }
        );

        // Filter button click handler
        $('#filterBtn').click(function() {
            var $btn = $(this);
            $btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i> Filtering...');

            setTimeout(function() {
                table.draw();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-filter"></i> Filter');
            }, 200);
        });

        // Reset button click handler
        $('#resetBtn').click(function() {
            var $btn = $(this);
            $btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i> Resetting...');

            setTimeout(function() {
                $('#start_date, #end_date').val('');
                table.draw();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-undo"></i> Reset');
            }, 200);
        });
    });

    // Back button functionality
    function goBack() {
        if (document.referrer) {
            // If there's a previous page in history
            window.history.back();
        } else {
            // Fallback to products page
            window.location.href = '<?= base_url('Controller_Products') ?>';
        }
    }
</script>