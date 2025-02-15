
<p><strong>Transaction Created:</strong> <?= date('Y-m-d H:i:s', strtotime($transaction->transaction_date)); ?></p>
<p><strong>Last Updated:</strong> <?= date('Y-m-d H:i:s', strtotime($transaction->updated_at)); ?></p>

<form action="<?= base_url('MedicineController/update_transaction') ?>" method="post">
    <input type="hidden" name="transaction_id" value="<?= $transaction->id; ?>">

    <label>Customer:</label>
    <select name="customer_id" required>
        <?php foreach ($customers as $customer): ?>
            <option value="<?= $customer->id; ?>" <?= ($customer->id == $transaction->customer_id) ? 'selected' : ''; ?>>
                <?= $customer->name; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Medicines:</label>
    <div id="medicine_fields">
        <?php foreach ($transaction_details as $detail): ?>
            <div>
                <input type="hidden" name="detail_id[]" value="<?= $detail->id; ?>">

                <select name="medicine_id[]" required>
                    <?php foreach ($medicines as $medicine): ?>
                        <option value="<?= $medicine->id; ?>" <?= ($medicine->id == $detail->medicine_id) ? 'selected' : ''; ?>>
                            <?= $medicine->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Given:</label>
                <input type="number" name="quantity_given[]" value="<?= $detail->quantity_given; ?>" required oninput="calculateBalance(this)">

                <label>Used:</label>
                <input type="number" name="quantity_used[]" value="<?= $detail->quantity_used; ?>" required oninput="calculateBalance(this)">

                <label>Returned:</label>
                <input type="number" name="quantity_returned[]" value="<?= $detail->quantity_returned; ?>" required oninput="calculateBalance(this)">

                <label>Balance:</label>
                <input type="number" name="balance_quantity[]" value="<?= $detail->quantity_given - ($detail->quantity_used + $detail->quantity_returned); ?>" disabled>

                <button type="button" onclick="removeField(this)">Remove</button>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="submit">Update Transaction</button>
</form>

<script>
function calculateBalance(input) {
    let row = input.parentElement;
    let given = row.querySelector('input[name="quantity_given[]"]').value;
    let used = row.querySelector('input[name="quantity_used[]"]').value;
    let returned = row.querySelector('input[name="quantity_returned[]"]').value;
    let balanceField = row.querySelector('input[name="balance_quantity[]"]');

    given = parseInt(given) || 0;
    used = parseInt(used) || 0;
    returned = parseInt(returned) || 0;

    let balance = given - (used + returned);
    balanceField.value = balance;
}

function removeField(button) {
    button.parentElement.remove();
}
</script>
