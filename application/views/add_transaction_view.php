<!-- Add CSS for styling -->
<style>
    form {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
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
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        width: 100%;
        max-width: 200px;
    }

    button:hover {
        background-color: #0056b3;
    }

    .button-container {
        display: flex;
        justify-content: flex-start;
        margin-top: 10px;
    }

    .button-container button {
        margin-right: 10px;
        flex: 1;
    }

    .button-container button:last-child {
        margin-right: 0;
    }

    @media (max-width: 600px) {
        form {
            padding: 10px;
        }

        select,
        input[type="number"] {
            margin-bottom: 15px;
        }
    }

    .btn-small {
        padding: 5px 10px;
        font-size: 12px;
        max-width: 50px;
    }

    .table {
        width: 100%;
        table-layout: fixed;
        /* Ensures consistent column widths */
    }

    .table th,
    .table td {
        text-align: center;
        /* Center align the text in table cells */
    }

    .table td {
        padding: 10px;
        /* Add padding for better spacing */
    }
</style>

<!-- Include Select2 CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Outward Medicine</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Outward Medicine</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <form action="<?= base_url('MedicineController/add_transaction') ?>" method="post">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Manager:</label>
                            <select name="customer_id" class="select2" required>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer->id; ?>"><?= $customer->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="medicine_fields">
                            <tr class="product-entry">
                                <td>
                                    <select name="medicine_id[]" class="select2 form-control" required>
                                        <option value="">Select a medicine</option>
                                        <?php foreach ($medicines as $medicine): ?>
                                            <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="quantity_given[]" placeholder="Quantity Given" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-small" onclick="addMedicineField(this)">+</button>
                                    <button type="button" class="btn btn-danger btn-small" onclick="removeField(this)">−</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="button-container">
                        <button type="submit" class="btn btn-primary">Outward Medicine</button>
                        <a href="<?php echo base_url('Controller_Products/') ?>" class="btn btn-warning">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function addMedicineField(button) {
        const container = document.getElementById('medicine_fields');
        const newRow = document.createElement('tr');
        newRow.className = 'product-entry';
        newRow.innerHTML = `
            <td>
                <select name="medicine_id[]" class="select2" required>
                    <option value="">Select a medicine</option>
                    <?php foreach ($medicines as $medicine): ?>
                        <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="number" name="quantity_given[]" placeholder="Quantity Given" required>
            </td>
            <td>
                <button type="button" class="btn btn-success btn-small" onclick="addMedicineField(this)">+</button>
                <button type="button" class="btn btn-danger btn-small" onclick="removeField(this)">−</button>
            </td>
        `;
        container.appendChild(newRow);
        $('.select2').select2(); // Re-initialize Select2 for new fields
    }

    // Remove product entry
    function removeField(button) {
        const container = document.getElementById('medicine_fields');
        if (container.children.length > 1) {
            button.closest('tr').remove();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot remove the last row!',
                text: 'You need at least one row to proceed.',
                confirmButtonText: 'OK'
            });
        }
    }
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>