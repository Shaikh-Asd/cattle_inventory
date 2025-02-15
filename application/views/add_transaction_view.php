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

<!-- Add CSS for styling -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }

    form {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin: 10px 0 5px;
    }

    select, input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #28a745; /* Green */
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #218838; /* Darker green */
    }

    @media (max-width: 600px) {
        form {
            padding: 10px;
        }

        button {
            width: 100%;
        }
    }
</style>

<!-- Include Select2 CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<form action="<?= base_url('MedicineController/add_transaction') ?>" method="post">
    <label>Customer:</label>
    <select name="customer_id" class="select2" required>
        <?php foreach ($customers as $customer): ?>
            <option value="<?= $customer->id; ?>"><?= $customer->name; ?></option>
        <?php endforeach; ?>
    </select>

    <label>Medicines:</label>
    <div id="medicine_fields">
        <div>
            <select name="medicine_id[]" class="select2" required>
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
    div.innerHTML = `<select name="medicine_id[]" class="select2" required>
            <?php foreach ($medicines as $medicine): ?>
                <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="quantity_given[]" placeholder="Quantity Given" required>
        <button type="button" onclick="removeField(this)">Remove</button>`;
    container.appendChild(div);
    $('.select2').select2(); // Re-initialize Select2 for new fields
}

function removeField(button) {
    button.parentElement.remove();
}

// Initialize Select2 on page load
$(document).ready(function() {
    $('.select2').select2();
});
</script>
