
<div class="content-wrapper">
    <section class="content-header">
        <h1>
        Transaction

        </h1>
        <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View Transaction</li>
        </ol>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-lg-12">
                <div class="Box">
                    <!-- <table border="1">
                        <tr>
                            <th>Customer</th>
                            <th>Medicine</th>
                            <th>Quantity Given</th>
                            <th>Quantity Used</th>
                            <th>Quantity Returned</th>
                            <th>Update</th>
                        </tr>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= $transaction->customer_name; ?></td>
                                <td><?= $transaction->medicine_name; ?></td>
                                <td><?= $transaction->quantity_given; ?></td>
                                <td><?= $transaction->quantity_used; ?></td>
                                <td><?= $transaction->quantity_returned; ?></td>
                                <td>
                                    <form action="<?= base_url('MedicineController/update_transaction') ?>" method="post">
                                        <input type="hidden" name="transaction_id" value="<?= $transaction->id; ?>">
                                        <input type="number" name="quantity_used" placeholder="Used" required>
                                        <input type="number" name="quantity_returned" placeholder="Returned" required>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table> -->
        
        
                    <div class="table-responsive">
                        <!-- <input type="text" id="searchInput" placeholder="Search..." class="form-control mb-3"> -->
                        <table class="table table-bordered" id="transactionsTable">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Customer</th>
                                    <th>Medicine</th>
                                    <th>Quantity Given</th>
                                    <th>Quantity Used</th>
                                    <th>Quantity Returned</th>
                                    <th>Balance Quantity</th>
                                    <th>Transaction Created</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= $transaction->id; ?></td>
                                        <td><?= $transaction->customer_name; ?></td>
                                        <td><?= $transaction->medicine_name; ?></td>
                                        <td><?= $transaction->quantity_given; ?></td>
                                        <td><?= $transaction->quantity_used; ?></td>
                                        <td><?= $transaction->quantity_returned; ?></td>
                                        <td><?= $transaction->balance_quantity; ?></td>
                                        <td><?= date('jS M Y h:i A', strtotime($transaction->transaction_date)); ?></td>
                                        <td><?= date('jS M Y h:i A', strtotime($transaction->updated_at)); ?></td>
                                        <td>
                                            <form action="<?= base_url('MedicineController/edit_transaction/' . $transaction->transaction_id) ?>" method="get">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </form>
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

<!-- Include jQuery and DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#transactionsTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10 // Set default number of rows per page
        });
    });
</script>