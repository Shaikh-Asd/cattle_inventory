

<!-- Add CSS for styling -->
<style>

    form {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        /* Stack elements vertically */
    }

    label {
        display: block;
        margin: 10px 0 5px;
    }

    select,
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #007bff;
        /* Blue */
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        margin-top: 10px;
        /* Add margin for spacing */
        width: auto;
        /* Set width to auto */
        max-width: 200px;
        /* Set a maximum width */
    }

    button:hover {
        background-color: #0056b3;
        /* Darker blue */
    }

    button i {
        margin-right: 5px;
        /* Space between icon and text */
    }


    .button-container {
        display: flex;
        /* Use flexbox for button alignment */
        justify-content: flex-start;
        /* Align buttons to the start */
        margin-top: 10px;
        /* Add margin for spacing */
    }

    .button-container button {
        margin-right: 10px;
        /* Add space between buttons */
    }

    @media (max-width: 600px) {
        form {
            padding: 10px;
        }

        button {
            width: auto;
            /* Maintain auto width on smaller screens */
        }

        /* Adjust input and select fields for smaller screens */
        select,
        input[type="number"] {
            margin-bottom: 15px;
            /* Increase margin for better spacing */
        }
    }
</style>

<!-- Include Select2 CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="content-wrapper">
    <section class="content-header">
            <h1>
            Transaction

            </h1>
            <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Add Transaction</li>
            </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
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
                        <button type="button" onclick="removeField(this)"><i class="fas fa-trash"></i> Remove</button>
                    </div>
                </div>
    
                <div class="button-container">
                    <button type="button" class="btn btn-success" onclick="addMedicineField()"><i class="fas fa-plus"></i> Add Medicine</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-check"></i> Add Transaction</button>
                </div>
            </form>
            </div>
        </div>
    </section>
</div>

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
        <button type="button" onclick="removeField(this)"><i class="fas fa-trash"></i> Remove</button>`;
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