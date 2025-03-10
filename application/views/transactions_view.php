<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Outward Medicines

        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Outward Medicines</li>
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
                                        <td><?= $transaction->transaction_id; ?></td>
                                        <td><a href="<?= base_url('MedicineController/customer_transactions/' . $transaction->customer_id) ?>"><?= $transaction->customer_name; ?></a></td>
                                        <td><?= $transaction->medicine_names; ?></td>
                                        <td><?= $transaction->quantity_given; ?></td>
                                        <td><?= date('jS M Y h:i A', strtotime($transaction->transaction_date)); ?></td>
                                        <td><?= date('jS M Y h:i A', strtotime($transaction->updated_at)); ?></td>
                                        <td>
                                            <p style="display:flex;">

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
<div id="transactionModal" style="display: none; position: fixed; top: 20%; left: 50%; width: 40%; transform: translate(-50%, 0); background: white; padding: 20px; border: 1px solid black;">
    <h3>Outward Medicines Details</h3>
    <p id="transactionInfo"></p>
    <button onclick="closeModal()" class="btn btn-secondary">Close</button>
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
                let formattedDate = formatDate(data.transaction_date);

                let details = `
            <strong>Manager Name:</strong> ${data.customer_name}<br>
            <strong>Outward Date:</strong> ${formattedDate}<br>            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Quantity Given</th>
                    </tr>
                </thead>
                <tbody>
        `;

                // Loop through the medicines and add rows
                data.medicines.forEach(med => {
                    let balance = med.quantity_given - (med.quantity_used + med.quantity_returned);
                    details += `
                <tr>
                    <td>${med.name}</td>
                    <td>${med.quantity_given}</td>
                </tr>
            `;
                });

                details += `
                </tbody>
            </table>
        `;

                // Update modal content
                document.getElementById("transactionInfo").innerHTML = details;
                document.getElementById("transactionModal").style.display = "block";
            })
            .catch(error => console.error("Error fetching transaction details:", error));
    }

    function closeModal() {
        document.getElementById("transactionModal").style.display = "none";
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear();
        const hours = date.getHours();
        const minutes = date.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = hours % 12 || 12; // Convert to 12-hour format
        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

        // Add ordinal suffix to day
        const ordinalSuffix = (day) => {
            if (day > 3 && day < 21) return 'th'; // 4-20
            switch (day % 10) {
                case 1:
                    return 'st';
                case 2:
                    return 'nd';
                case 3:
                    return 'rd';
                default:
                    return 'th';
            }
        };

        return `${day}${ordinalSuffix(day)} ${month} ${year} ${formattedHours}:${formattedMinutes} ${ampm}`;
    }
</script>