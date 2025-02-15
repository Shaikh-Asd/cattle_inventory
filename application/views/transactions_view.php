<div class="container mt-4">
    <h2 class="text-center">Transaction Records</h2>
    <div class="row">
        <div class="col-lg-12">
<<<<<<< HEAD
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
   

            <table border="1">
    <tr>
        <th>Transaction Created</th>
        <th>Last Updated</th>
        <th>Customer</th>
        <th>Medicine</th>
        <th>Quantity Given</th>
        <th>Quantity Used</th>
        <th>Quantity Returned</th>
        <th>Balance Quantity</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($transactions as $transaction): ?>
        <tr>
            <td><?= date('Y-m-d H:i:s', strtotime($transaction->transaction_date)); ?></td>
            <td><?= date('Y-m-d H:i:s', strtotime($transaction->updated_at)); ?></td>
            <td><?= $transaction->customer_name; ?></td>
            <td><?= $transaction->medicine_name; ?></td>
            <td><?= $transaction->quantity_given; ?></td>
            <td><?= $transaction->quantity_used; ?></td>
            <td><?= $transaction->quantity_returned; ?></td>
            <td><?= $transaction->balance_quantity; ?></td>
            <td>
                <a href="<?= base_url('MedicineController/edit_transaction/'.$transaction->transaction_id) ?>">Edit</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


          </div>
=======
            <div class="Box">
                <!-- Updated table for better UI/UX and responsiveness -->
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Medicine</th>
                            <th>Quantity Given</th>
                            <th>Quantity Used</th>
                            <th>Quantity Returned</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= $transaction->customer_name; ?></td>
                            <td><?= $transaction->medicine_name; ?></td>
                            <td><?= $transaction->quantity_given; ?></td>
                            <td><?= $transaction->quantity_used; ?></td>
                            <td><?= $transaction->quantity_returned; ?></td>
                            <td>
                                <form action="<?= base_url('MedicineController/update_transaction') ?>" method="post" class="form-inline">
                                    <input type="hidden" name="transaction_detail_id" value="<?= $transaction->id; ?>">
                                    <input type="number" name="quantity_used" placeholder="Used" required class="form-control mx-1" style="background-color: #f8f9fa; border: 1px solid #007bff;">
                                    <input type="number" name="quantity_returned" placeholder="Returned" required class="form-control mx-1" style="background-color: #f8f9fa; border: 1px solid #007bff;">
                                    <button type="submit" class="btn" style="background-color: #007bff; color: white;">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
>>>>>>> 72aacfff535f08ea3be70bf47d9713d521f821e8
        </div>
    </div>
</div>