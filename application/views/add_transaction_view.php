<!-- <form action="<?= base_url('MedicineController/add_transaction') ?>" method="post">
    <label>Customer:</label>
    <select name="customer_id" required>
        <?php foreach ($customers as $customer): ?>
            <option value="<?= $customer->id; ?>"><?= $customer->name; ?></option>
        <?php endforeach; ?>
    </select>
    
    <label>Medicine:</label>
    <select name="medicine_id" required>
        <?php foreach ($medicines as $medicine): ?>
            <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
        <?php endforeach; ?>
    </select>
    
    <label>Quantity Given:</label>
    <input type="number" name="quantity_given" required>

    <button type="submit">Add Transaction</button>
</form> -->



<form action="<?= base_url('MedicineController/add_transaction') ?>" method="post">
    <label>Customer:</label>
    <select name="customer_id" required>
        <?php foreach ($customers as $customer): ?>
            <option value="<?= $customer->id; ?>"><?= $customer->name; ?></option>
        <?php endforeach; ?>
    </select>

    <label>Medicines:</label>
    <div id="medicine_fields">
        <div>
            <select name="medicine_id[]" required>
                <?php foreach ($medicines as $medicine): ?>
                    <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantity_given[]" placeholder="Quantity Given" required>
            <button type="button" onclick="removeField(this)">Remove</button>
        </div>
    </div>
    
    <button type="button" onclick="addMedicineField()">Add Medicine</button>
    <button type="submit">Add Transaction</button>
</form>

<script>
function addMedicineField() {
    let container = document.getElementById('medicine_fields');
    let div = document.createElement('div');
    div.innerHTML = `<select name="medicine_id[]" required>
            <?php foreach ($medicines as $medicine): ?>
                <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="quantity_given[]" placeholder="Quantity Given" required>
        <button type="button" onclick="removeField(this)">Remove</button>`;
    container.appendChild(div);
}

function removeField(button) {
    button.parentElement.remove();
}
</script>
