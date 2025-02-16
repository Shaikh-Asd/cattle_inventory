<div class="content-wrapper">
    <section class="content-header">
        <h1>
        Transaction

        </h1>
        <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?= $customer->name; ?> Transaction</li>
        </ol>
    </section>
    <section class="content">
        <h2>Transaction History for <b><?= $customer->name; ?></b></h2>
    
        <div class="row">
            <div class="col-lg-12">
                <div class="Box">
                    <!-- Date Filter -->
                    <form method="get">
                        <label>Start Date:</label>
                        <input type="date" name="start_date" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" required>
                        
                        <label>End Date:</label>
                        <input type="date" name="end_date" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" required>
                    
                        <button type="submit">Filter</button>
                    </form>
                    <div class="table-responsive">
                        <table border="1" class="table table-bordered" id="transactionsTable">
                            <tr>
                                <th>Transaction Date</th>
                                <th>Last Updated</th>
                                <th>Medicine</th>
                                <th>Quantity Given</th>
                                <th>Quantity Used</th>
                                <th>Quantity Returned</th>
                                <th>Balance Quantity</th>
                            </tr>
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= date('Y-m-d H:i:s', strtotime($transaction->transaction_date)); ?></td>
                                        <td><?= date('Y-m-d H:i:s', strtotime($transaction->updated_at)); ?></td>
                                        <td><?= $transaction->medicine_name; ?></td>
                                        <td><?= $transaction->quantity_given; ?></td>
                                        <td><?= $transaction->quantity_used; ?></td>
                                        <td><?= $transaction->quantity_returned; ?></td>
                                        <td><?= $transaction->balance_quantity; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">No transactions found for this period.</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
