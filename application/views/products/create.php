<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  var $j = jQuery.noConflict(true);
</script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
  .select2-container {
    width: 100% !important;
  }
  .error-field {
    border-color: #dc3545 !important;
  }
  .error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
  }
  .form-group.has-error .select2-container--default .select2-selection--single {
    border-color: #dc3545;
  }
  .highlight-empty {
    animation: highlight 1s ease-in-out;
  }
  @keyframes highlight {
    0% { background-color: #fff; }
    50% { background-color: #fff3cd; }
    100% { background-color: #fff; }
  }
  /* Enhanced dropdown styles */
  .select2-container--default .select2-selection--single {
    height: 38px;
    border-radius: 4px;
    border: 1px solid #d2d6de;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding-left: 12px;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
  }
  .select2-container--default.select2-container--open .select2-selection--single {
    border-color: #dc3545;
    box-shadow: 0 0 5px rgba(220, 53, 69, 0.3);
  }
  .select2-dropdown {
    border-color: #dc3545;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #dc3545;
  }
  .required-field-label {
    position: relative;
    display: inline-block;
  }
  .required-field-label:after {
    content: "*";
    color: #dc3545;
    margin-left: 4px;
    font-weight: bold;
  }
  .dropdown-focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 5px rgba(220, 53, 69, 0.5) !important;
  }
</style>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Inward Medicines
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Inward Medicines</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="box box-primary">
        <!-- /.box-header -->
        <form role="form" action="<?php base_url('users/create') ?>" method="post" enctype="multipart/form-data">
          <div class="box-body">
            <?php if($this->session->flashdata('validation_errors')): ?>
              <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Please fix the following errors:</h4>
                <?php echo $this->session->flashdata('validation_errors'); ?>
              </div>
            <?php endif; ?>

            <div class="row" style="display: flex; align-items: center;">
              <div class="col-lg-3">
                <div class="form-group <?php echo form_error('customers') ? 'has-error' : ''; ?>">
                  <label for="customers" class="required-field-label">Vendor Name</label>
                  <select class="form-control select2" id="customers" name="customers">
                    <option value="">Select a vendor</option>
                    <?php foreach ($customers as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>" <?php echo (set_select('customers', $v['id']) || (isset($form_data['customers']) && $form_data['customers'] == $v['id'])) ? 'selected' : ''; ?>><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                  <?php echo form_error('customers', '<p class="error-message">', '</p>'); ?>
                </div>
              </div>
            </div>

            <table class="table">
              <thead>
                <tr>
                  <th>Medicine <span class="text-danger">*</span></th>
                  <th>Quantity <span class="text-danger">*</span></th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="productFields">
                <?php 
                $product_names = isset($form_data['product_name']) ? $form_data['product_name'] : array('');
                $quantities = isset($form_data['qty']) ? $form_data['qty'] : array('');
                foreach($product_names as $index => $product_name): 
                ?>
                <tr class="product-entry">
                  <td>
                    <div class="form-group <?php echo form_error('product_name[]') ? 'has-error' : ''; ?>">
                      <select class="form-control select2 medicine-select" name="product_name[]">
                        <option value="">Select a medicine</option>
                        <?php foreach ($medicines as $medicine): ?>
                          <option value="<?= $medicine->id; ?>" <?php echo (set_select('product_name[]', $medicine->id) || (isset($product_names[$index]) && $product_names[$index] == $medicine->id)) ? 'selected' : ''; ?>><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
                        <?php endforeach; ?>
                      </select>
                      <?php if($index === 0) echo form_error('product_name[]', '<p class="error-message">', '</p>'); ?>
                    </div>
                  </td>
                  <td>
                    <div class="form-group <?php echo form_error('qty[]') ? 'has-error' : ''; ?>">
                      <input type="text" class="form-control" name="qty[]" placeholder="Enter Quantity" autocomplete="off" value="<?php echo isset($quantities[$index]) ? $quantities[$index] : ''; ?>" />
                      <?php if($index === 0) echo form_error('qty[]', '<p class="error-message">', '</p>'); ?>
                    </div>
                  </td>
                  <td>
                    <button type="button" class="btn btn-danger removeProduct">Remove row</button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3">
                    <button type="button" class="btn btn-success addProduct">Add New row</button>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Inward Medicines</button>
            <a href="<?php echo base_url('Controller_Products/') ?>" class="btn btn-warning">Back</a>
          </div>
        </form>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- col-md-12 -->
</div>
<!-- /.row -->


</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // Initialize Select2 with enhanced options
    $(".select2").select2({
      width: '100%',
      placeholder: function() {
        return $(this).data('placeholder') || 'Select an option';
      },
      allowClear: true
    });
    
    // Set placeholder for vendor dropdown
    $("#customers").data('placeholder', 'Select a vendor');
    
    // Set placeholder for medicine dropdowns
    $(".medicine-select").data('placeholder', 'Select a medicine');

    // Function to highlight empty fields
    function highlightEmptyFields() {
      $('.form-control').each(function() {
        if ($(this).val() === '') {
          $(this).addClass('error-field highlight-empty');
        } else {
          $(this).removeClass('error-field highlight-empty');
        }
      });
    }

    // Add this script to handle adding and removing product fields
    $(document).on('click', '.addProduct', function() {
      var newProductEntry = `
        <tr class="product-entry">
          <td>
            <div class="form-group">
              <select class="form-control select2 medicine-select" name="product_name[]" data-placeholder="Select a medicine">
                <option value="">Select a medicine</option>
                <?php foreach ($medicines as $medicine): ?>
                  <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>
          </td>
          <td>
            <div class="form-group">
              <input type="text" class="form-control" name="qty[]" placeholder="Enter Quantity" autocomplete="off" />
            </div>
          </td>
          <td>
            <button type="button" class="btn btn-danger removeProduct">Remove row</button>
          </td>
        </tr>`;
      $("#productFields").append(newProductEntry);

      // Re-initialize Select2 for the newly added select elements
      $(".select2").select2({
        width: '100%',
        placeholder: function() {
          return $(this).data('placeholder') || 'Select an option';
        },
        allowClear: true
      });
    });

    // Remove product entry
    $(document).on('click', '.removeProduct', function() {
      if ($('#productFields tr').length > 1) {
        $(this).closest('tr').remove();
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Cannot remove the last row!',
          text: 'You need at least one row to proceed.',
          confirmButtonText: 'OK'
        });
      }
    });

    // Add client-side validation
    $('form').on('submit', function(e) {
      var isValid = true;
      var errorMessage = '';
      var emptyFields = [];

      // Validate vendor selection
      if ($('#customers').val() === '') {
        isValid = false;
        errorMessage += 'Please select a vendor.\n';
        emptyFields.push('#customers');
        // Focus on vendor dropdown
        $('#customers').next('.select2-container').find('.select2-selection').addClass('dropdown-focus');
      }

      // Validate each product row
      $('.product-entry').each(function() {
        var medicineSelect = $(this).find('select[name="product_name[]"]');
        var quantityInput = $(this).find('input[name="qty[]"]');

        if (medicineSelect.val() === '') {
          isValid = false;
          errorMessage += 'Please select a medicine for all rows.\n';
          emptyFields.push(medicineSelect);
          // Focus on medicine dropdown
          medicineSelect.next('.select2-container').find('.select2-selection').addClass('dropdown-focus');
        }

        var quantity = quantityInput.val();
        if (quantity === '' || isNaN(quantity) || parseInt(quantity) <= 0) {
          isValid = false;
          errorMessage += 'Please enter a valid quantity (greater than 0) for all rows.\n';
          emptyFields.push(quantityInput);
        }
      });

      if (!isValid) {
        e.preventDefault();
        
        // Highlight empty fields
        emptyFields.forEach(function(field) {
          $(field).addClass('error-field highlight-empty');
        });
        
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: errorMessage,
          confirmButtonText: 'OK'
        });
      }
    });

    // Remove error styling when field is filled
    $(document).on('change', 'select, input', function() {
      if ($(this).val() !== '') {
        $(this).removeClass('error-field highlight-empty');
        $(this).closest('.form-group').removeClass('has-error');
        $(this).next('.select2-container').find('.select2-selection').removeClass('dropdown-focus');
      }
    });

    // Focus on vendor dropdown when page loads
    setTimeout(function() {
      $('#customers').next('.select2-container').find('.select2-selection').addClass('dropdown-focus');
    }, 500);

    // Highlight empty fields on page load
    highlightEmptyFields();
  });
</script>