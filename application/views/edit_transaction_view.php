<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Transaction

        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Transaction</li>
        </ol>
    </section>
    <section class="content">

        <p><strong>Created:</strong> <?= date('jS M Y h:i A', strtotime($transaction->transaction_date)); ?></p>
        <p><strong>Updated:</strong> <?= date('jS M Y h:i A', strtotime($transaction->updated_at)); ?></p>

        <form action="<?= base_url('MedicineController/update_transaction') ?>" method="post" style="max-width: 600px;">
            <input type="hidden" name="transaction_id" value="<?= $transaction->id; ?>">

            <div style="display: flex;  margin-bottom: 15px;">
                <label style="flex: 1; margin-right: 10px;">Customer:</label>
                <select name="customer_id" required style="flex: 2; padding: 8px;" class="select2">
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= $customer->id; ?>" <?= ($customer->id == $transaction->customer_id) ? 'selected' : ''; ?>>
                            <?= $customer->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- <label style="display: block; margin-bottom: 5px;">Medicines:</label> -->
            <div id="medicine_fields">
                <?php foreach ($transaction_details as $detail): ?>
                    <div style="display: flex; align-items: center; margin-bottom: 15px; padding: 10px; border-radius: 5px;">
                        <input type="hidden" name="detail_id[]" value="<?= $detail->id; ?>">

                        <label style="flex: 1; margin-right: 5px;">Medicine:</label>
                        <select name="medicine_id[]" required style="flex: 2; padding: 8px; margin-right: 5px;" class="select2">
                            <?php foreach ($medicines as $medicine): ?>
                                <!-- <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option> -->
                                <option value="<?= $medicine->id; ?>" <?= ($medicine->id == $detail->medicine_id) ? 'selected' : ''; ?>>
                                    <?= $medicine->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label style="flex: 1; margin-right: 5px;">Given:</label>
                        <input type="number" name="quantity_given[]" value="<?= $detail->quantity_given; ?>" required oninput="calculateBalance(this)" style="flex: 1; padding: 8px; margin-right: 5px;">

                        <label style="flex: 1; margin-right: 5px;">Used:</label>
                        <input type="number" name="quantity_used[]" value="<?= $detail->quantity_used; ?>" required oninput="calculateBalance(this)" style="flex: 1; padding: 8px; margin-right: 5px;">

                        <label style="flex: 1; margin-right: 5px;">Returned:</label>
                        <input type="number" name="quantity_returned[]" value="<?= $detail->quantity_returned; ?>" required oninput="calculateBalance(this)" style="flex: 1; padding: 8px; margin-right: 5px;">

                        <label style="flex: 1; margin-right: 5px;">Balance:</label>
                        <input type="number" name="balance_quantity[]" value="<?= $detail->quantity_given - ($detail->quantity_used + $detail->quantity_returned); ?>" disabled style="flex: 1; padding: 8px; margin-right: 5px;">

                        <button type="button" onclick="removeField(this)" style="background-color: #ff4d4d; color: white; border: none; padding: 8px 12px; border-radius: 5px;">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">Update Transaction</button>
        </form>
    </section>
</div>

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

    $(document).ready(function() {
        $('.select2').select2(); // Initialize Select2 for all select elements with class 'select2'
    });
</script>