
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
                                        <td><a href="<?= base_url('MedicineController/customer_transactions/' . $transaction->customer_id) ?>"><?= $transaction->customer_name; ?></a></td>
                                        <td><?= $transaction->medicine_name; ?></td>
                                        <td><?= $transaction->quantity_given; ?></td>
                                        <td><?= $transaction->quantity_used; ?></td>
                                        <td><?= $transaction->quantity_returned; ?></td>
                                        <td><?= $transaction->balance_quantity; ?></td>
                                        <td><?= date('jS M Y h:i A', strtotime($transaction->transaction_date)); ?></td>
                                        <td><?= date('jS M Y h:i A', strtotime($transaction->updated_at)); ?></td>
                                        <td>
                                            <p style="display:flex;">
                                                <span style="margin-right: 5px">
                                                    <a href="<?= base_url('MedicineController/edit_transaction/' . $transaction->transaction_id) ?>" >
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                    </a>
                                                </span>
                                                <span>
                                                    <button class="btn btn-primary btn-sm" onclick="showTransactionDetails(<?= $transaction->transaction_id; ?>)">View Details</button>
                                                </span>
                                            </p>
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
<div id="transactionModal" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, 0); background: white; padding: 20px; border: 1px solid black;">
    <h3>Transaction Details</h3>
    <p id="transactionInfo"></p>
    <button onclick="closeModal()">Close</button>
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

    function showTransactionDetails(transactionId) {
    fetch("<?= base_url('MedicineController/get_transaction_details/'); ?>" + transactionId)
        .then(response => response.json())
        .then(data => {
            let details = "<strong>Customer:</strong> " + data.customer_name + "<br>";
            details += "<strong>Transaction Date:</strong> " + data.transaction_date + "<br>";
            details += "<strong>Medicines:</strong><br><ul>";
            data.medicines.forEach(med => {
                details += "<li>" + med.name + " (Given: " + med.quantity_given + ", Used: " + med.quantity_used + ", Returned: " + med.quantity_returned + ", Balance: " + (med.quantity_given - (med.quantity_used + med.quantity_returned)) + ")</li>";
            });
            details += "</ul>";
            document.getElementById("transactionInfo").innerHTML = details;
            document.getElementById("transactionModal").style.display = "block";
        });
}

function closeModal() {
    document.getElementById("transactionModal").style.display = "none";
}
</script>