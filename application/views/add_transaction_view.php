<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outward Medicine - Cattle Inventory</title>
    
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .content-wrapper {
            padding: 20px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        select:focus,
        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
            max-width: 90px;
        }

        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 500;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ccc;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 10px;
            }

            form {
                padding: 15px;
            }

            .button-container {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
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
                    <form action="<?php echo base_url('MedicineController/add_transaction'); ?>" method="post" id="medicineForm">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="customer_id">Manager:</label>
                                <select name="customer_id" id="customer_id" class="select2" required>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?php echo htmlspecialchars($customer->id); ?>">
                                            <?php echo htmlspecialchars($customer->name); ?>
                                        </option>
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
                                                <option value="<?php echo htmlspecialchars($medicine->id); ?>">
                                                    <?php echo htmlspecialchars($medicine->name); ?> 
                                                    (Stock: <?php echo htmlspecialchars($medicine->stock); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="quantity_given[]" 
                                               placeholder="Quantity Given" min="1" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-small" onclick="removeField(this)">
                                            Remove row
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" style="text-align: left;">
                                        <button type="button" class="btn btn-success btn-small" onclick="addMedicineField()">
                                            Add New row
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="button-container">
                            <button type="submit" class="btn btn-primary">Outward Medicine</button>
                            <a href="<?php echo base_url('Controller_Products/'); ?>" class="btn btn-warning">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- jQuery and Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();
            
            // Handle form submission
            $('#medicineForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate quantities
                let isValid = true;
                $('input[name="quantity_given[]"]').each(function() {
                    const quantity = parseInt($(this).val());
                    const stock = parseInt($(this).closest('tr').find('select option:selected').text().match(/Stock: (\d+)/)[1]);
                    
                    if (quantity > stock) {
                        isValid = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Quantity',
                            text: 'Quantity cannot exceed available stock',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }
                });

                if (!isValid) return;

                // Show processing alert
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your request',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit the form
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Medicine transaction added successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?php echo base_url("MedicineController/customer_medicine_view"); ?>';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            showConfirmButton: true
                        });
                    }
                });
            });
        });

        function addMedicineField() {
            const container = document.getElementById('medicine_fields');
            const newRow = document.createElement('tr');
            newRow.className = 'product-entry';
            newRow.innerHTML = `
                <td>
                    <select name="medicine_id[]" class="select2" required>
                        <option value="">Select a medicine</option>
                        <?php foreach ($medicines as $medicine): ?>
                            <option value="<?php echo htmlspecialchars($medicine->id); ?>">
                                <?php echo htmlspecialchars($medicine->name); ?> 
                                (Stock: <?php echo htmlspecialchars($medicine->stock); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="quantity_given[]" placeholder="Quantity Given" min="1" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-small" onclick="removeField(this)">
                        Remove row
                    </button>
                </td>
            `;
            container.appendChild(newRow);
            $('.select2').select2();
        }

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
    </script>
</body>
</html>