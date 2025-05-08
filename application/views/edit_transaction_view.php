<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inward Medicines - Cattle Inventory</title>
    
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .content-wrapper {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 34px;
            border: 1px solid #ccc;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
        }

        .form-control {
            height: 34px;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn {
            padding: 6px 12px;
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

        .medicine-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 10px;
        }

        .medicine-row > div {
            flex: 1;
        }

        .medicine-row > div:last-child {
            flex: 0 0 auto;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 10px;
            }

            .medicine-row {
                flex-direction: column;
                gap: 5px;
            }

            .medicine-row > div {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Edit Inward Medicines</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url('home'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Inward Medicines</li>
            </ol>
        </section>

        <section class="content">
            <form action="<?php echo base_url('MedicineController/update_transaction'); ?>" method="post" id="editTransactionForm">
                <input type="hidden" name="transaction_id" value="<?php echo htmlspecialchars($transaction->id); ?>">
                <input type="hidden" name="removed_detail_ids" id="removed_detail_ids" value="">

                <div class="form-group">
                    <label for="customer_id">Vendor Name</label>
                    <select name="customer_id" id="customer_id" required class="form-control select2">
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?php echo htmlspecialchars($customer->id); ?>" 
                                    <?php echo ($customer->id == $transaction->customer_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($customer->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <label>Medicine</label>
                        </div>
                        <div class="col-md-5">
                            <label>Quantity</label>
                        </div>
                        <div class="col-md-2">
                            <label>Action</label>
                        </div>
                    </div>

                    <div id="medicine_fields">
                        <?php foreach ($transaction_details as $detail): ?>
                            <div class="row medicine-row" data-detail-id="<?php echo htmlspecialchars($detail->id); ?>">
                                <div class="col-md-5">
                                    <input type="hidden" name="detail_id[]" value="<?php echo htmlspecialchars($detail->id); ?>">
                                    <select name="medicine_id[]" required class="form-control select2">
                                        <?php foreach ($medicines as $medicine): ?>
                                            <option value="<?php echo htmlspecialchars($medicine->id); ?>" 
                                                    <?php echo ($medicine->id == $detail->medicine_id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($medicine->name); ?> 
                                                (Stock: <?php echo htmlspecialchars($medicine->stock); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" name="quantity_given[]" 
                                           value="<?php echo htmlspecialchars($detail->quantity_given); ?>" 
                                           class="form-control" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-success" id="addNewRow">Add New Row</button>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="<?php echo base_url('MedicineController/view_transactions'); ?>" class="btn btn-warning">Back</a>
                </div>
            </form>
        </section>
    </div>

    <!-- jQuery and Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let removedDetailIds = [];

        // Template for new row
        function getNewRowHtml() {
            return `
                <div class="row medicine-row" data-detail-id="new">
                    <div class="col-md-5">
                        <input type="hidden" name="detail_id[]" value="new">
                        <select name="medicine_id[]" required class="form-control select2">
                            <option value="">Select Medicine</option>
                            <?php foreach ($medicines as $medicine): ?>
                                <option value="<?php echo htmlspecialchars($medicine->id); ?>">
                                    <?php echo htmlspecialchars($medicine->name); ?> 
                                    (Stock: <?php echo htmlspecialchars($medicine->stock); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="quantity_given[]" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                    </div>
                </div>
            `;
        }

        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%'
            });

            // Handle adding new row
            $('#addNewRow').on('click', function() {
                const newRow = $(getNewRowHtml());
                $('#medicine_fields').append(newRow);
                newRow.find('.select2').select2();
            });

            // Handle row removal
            $(document).on('click', '.remove-row', function() {
                const row = $(this).closest('.medicine-row');
                const detailId = row.data('detail-id');
                
                if (detailId && detailId !== 'new') {
                    removedDetailIds.push(detailId);
                    $('#removed_detail_ids').val(removedDetailIds.join(','));
                }
                
                row.remove();
            });

            // Handle form submission
            $('#editTransactionForm').on('submit', function(e) {
                e.preventDefault();

                // Validate quantities
                let isValid = true;
                $('input[name="quantity_given[]"]').each(function() {
                    const quantity = parseInt($(this).val());
                    const stock = parseInt($(this).closest('.medicine-row').find('select option:selected').text().match(/Stock: (\d+)/)[1]);
                    
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
                    text: 'Please wait while we save your changes',
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
                            text: 'Transaction updated successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?php echo base_url("MedicineController/view_transactions"); ?>';
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
    </script>
</body>
</html>