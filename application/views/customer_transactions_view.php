<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#transactionsTable').DataTable({
            "paging": true,
            "searching": true,
            "responsive": true,
            "lengthMenu": [5, 10, 25, 50, 100], // Options for number of entries to show
            "pageLength": 10 // Default number of entries to show
        });
    });
</script>

<div class="content-wrapper">
    <section class="content-header">
        <h1>History for <b><?= $customer->name; ?></b></h1>
    </section>
        <section class="content">
            <div class="row  box-primary">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <!-- Date Filter -->
                        <form method="get" class="form-inline mb-3 box-primary">
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" required>
                            </div>
                            <div class="form-group mx-2">
                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset</button>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="transactionsTable">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Quantity Given</th>
                                        <th>Transaction Date</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)): ?>
                                        <?php foreach ($transactions as $transaction): ?>
                                            <tr>
                                                <td><?= $transaction->medicine_name; ?></td>
                                                <td><?= $transaction->quantity_given; ?></td>
                                                <td><?= date('Y-m-d H:i:s', strtotime($transaction->transaction_date)); ?></td>
                                                <td><?= date('Y-m-d H:i:s', strtotime($transaction->updated_at)); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7">No history found for this period.</td>
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

    <script>
        function resetFilters() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            // Optionally, you can submit the form to refresh the page without filters
            // document.forms[0].submit();
        }
    </script>