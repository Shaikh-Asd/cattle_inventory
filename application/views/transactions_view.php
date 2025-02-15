<div class="container mt-4">
    <h2 class="text-center">Transaction Records</h2>
    <div class="row">
        <div class="col-lg-12">
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
        </div>
    </div>
</div>