<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  var $j = jQuery.noConflict(true);
</script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Edit Inward Medicines


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
      <div class="col-md-12 col-xs-12">

    

        <div class="box">

          <!-- /.box-header -->
          <form role="form" action="<?php echo base_url('Controller_Products/update/' . $product_data['id']); ?>" method="post" enctype="multipart/form-data">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="form-group">
                <label for="customers">Vendor Name</label>
                <select class="form-control" id="customers" name="customers">
                  <option value="">Select a user</option>
                  <?php foreach ($customers as $k => $v): ?>
                    <option value="<?php echo $v['id'] ?>" <?php if ($product_data['customer_id'] == $v['id']) {
                      echo "selected='selected'";
                    } ?>><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>

              <table class="table">
                <thead>
                  <tr>
                    <th>Medicine</th>
                    <th>Qty</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="productFields"></tbody>
              </table>
              <button type="button" class="btn btn-success addProduct" id="addProduct">Add New row</button>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Save Changes</button>
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

<script>
  $(document).ready(function() {
    var allMedicines = <?php echo json_encode($medicines); ?>;

    // Helper: get medicine ID by name
    function getMedicineIdByName(name) {
      name = name.trim();
      for (var i = 0; i < allMedicines.length; i++) {
        if (allMedicines[i].name.trim() === name) {
          return allMedicines[i].id;
        }
      }
      return '';
    }

    function renderRow(selectedId, qty) {
      var options = '<option value="">Select a medicine</option>';
      allMedicines.forEach(function(med) {
      
          options += `<option value="${med.id}" ${selectedId == med.id ? 'selected' : ''}>${med.name} (Stock: ${med.stock})</option>`;
        
      });
      return `
      <tr class="product-entry">
        <td>
          <select class="form-control select2 medicine-select" name="product_name[]">${options}</select>
        </td>
        <td>
          <input type="text" class="form-control" name="qty[]" value="${qty || ''}" placeholder="Enter Quantity" autocomplete="off" />
        </td>
        <td>
          <button type="button" class="btn btn-danger removeProduct">Remove row</button>
        </td>
      </tr>
    `;
    }

    // Fetch and render rows as per API response
    var productId = <?php echo $product_data['id']; ?>;
    $.ajax({
      url: "<?php echo base_url('Controller_Products/fetchProductDataById/'); ?>" + productId,
      type: "GET",
      dataType: "json",
      success: function(data) {
        $("#customers").val(data.customer_id);
        $("#productFields").empty();

        // Handle both single and multiple medicine_id/qty
        var medicineIds = data.medicine_id;
        var qtys = data.qty;

        // Normalize medicineIds to array
        if (!Array.isArray(medicineIds)) {
          if (typeof medicineIds === 'string' && medicineIds.includes(',')) {
            medicineIds = medicineIds.split(',').map(s => s.trim());
          } else if (typeof medicineIds === 'string' && medicineIds.trim() !== '') {
            medicineIds = [medicineIds.trim()];
          } else {
            medicineIds = [];
          }
        }

        // Normalize qtys to array
        if (!Array.isArray(qtys)) {
          if (typeof qtys === 'string' && qtys.includes(',')) {
            qtys = qtys.split(',').map(s => s.trim());
          } else if (typeof qtys !== 'undefined' && qtys !== null) {
            qtys = [qtys];
          } else {
            qtys = [];
          }
        }

        // Render rows
        for (var i = 0; i < medicineIds.length; i++) {
          var medId = medicineIds[i];
          var qty = qtys[i] || '';
          $("#productFields").append(renderRow(medId, qty));
        }
        if (!medicineIds.length) {
          $("#productFields").append(renderRow('', ''));
        }
        $('.select2').select2({
          width: '100%'
        });
      }
    });

    // Add new product row
    $("#addProduct").on('click', function() {
      $("#productFields").append(renderRow('', ''));
      $('.select2').select2({
        width: '100%'
      });
    });

    // Remove product row (at least one must remain)
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

    // Client-side validation on submit
    $('form').on('submit', function(e) {
      console.log('submit');
      return;
      var isValid = true;
      var errorMessage = '';
      var emptyFields = [];

      // Validate vendor selection
      if ($('#customers').val() === '') {
        isValid = false;
        errorMessage += 'Please select a user (Vendor Name).\n';
        emptyFields.push($('#customers'));
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
          field.addClass('error-field highlight-empty');
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
  });
</script>

<style>
  .error-field {
    border-color: #dc3545 !important;
  }

  .highlight-empty {
    animation: highlight 1s ease-in-out;
  }

  @keyframes highlight {
    0% {
      background-color: #fff;
    }

    50% {
      background-color: #fff3cd;
    }

    100% {
      background-color: #fff;
    }
  }

  .dropdown-focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 5px rgba(220, 53, 69, 0.5) !important;
  }
</style>