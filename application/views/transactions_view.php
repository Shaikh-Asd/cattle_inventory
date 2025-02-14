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


            <table border="1">
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
                    <input type="hidden" name="transaction_detail_id" value="<?= $transaction->id; ?>">
                    <input type="number" name="quantity_used" placeholder="Used" required>
                    <input type="number" name="quantity_returned" placeholder="Returned" required>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

          </div>
        </div>
      </div>