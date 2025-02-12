<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Outward Medicines
      <small></small>
    </h1>
    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Outward Medicines</li>
    </ol>
  </section>


  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif ($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>


        <div class="box">

          <!-- /.box-header -->
          <form role="form" action="<?php base_url('Controller_Orders/create') ?>" method="post" class="form-horizontal">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="form-group">
                <label for="taken_by" class="col-sm-2 control-label">Outward By</label>
                <div class="col-sm-10">
                  <select class="form-control select2" id="taken_by" name="taken_by" required>
                    <option value="">Select a user</option>
                    <?php foreach ($customers as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="availability" class="col-sm-2 control-label">Availability</label>
                <div class="col-sm-10">
                  <select class="form-control" id="availability" name="availability" required>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-12">
                  <button type="button" id="add_row" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
                </div>
              </div>

              <table class="table table-bordered" id="product_info_table">
                <thead>
                  <tr>
                    <th>Medicine</th>
                    <th>Available Qty</th>
                    <th>Qty</th>
                    <th>Action</th>
                  </tr>
                </thead>

                <tbody>
                  <tr id="row_1">
                    <td>
                      <select class="form-control select2 product" data-row-id="1" id="product_1" name="product[]" required>
                        <option value="">Select a medicine</option>
                        <?php foreach ($medicines as $k => $v): ?>
                          <?php
                          $quantityData = $this->model_medicines->getMedicineQuantity($v['id']);
                          $quantity = isset($quantityData['qty']) ? $quantityData['qty'] : 0; // Access the 'qty' key
                          ?>
                          <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] . ' (' . $quantity . ')' ?></option>
                        <?php endforeach ?>
                      </select>
                    </td>
                    <td><input type="text" name="available_qty[]" id="available_qty_1" class="form-control" disabled></td>
                    <td><input type="text" name="qty[]" id="qty_1" class="form-control" required></td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm" onclick="removeRow('1')"><i class="fa fa-close"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="form-group">
                <div class="col-sm-12">
                  <button type="submit" class="btn btn-success">Save Changes</button>
                  <a href="<?php echo base_url('Controller_Orders/') ?>" class="btn btn-warning">Back</a>
                </div>
              </div>
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

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    $(".select_group").select2();
    // $("#description").wysihtml5();

    $("#mainOrdersNav").addClass('active');
    $("#addOrderNav").addClass('active');

    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' +
      'onclick="alert(\'Call your custom code here.\')">' +
      '<i class="glyphicon glyphicon-tag"></i>' +
      '</button>';

    var rowCount = 1; // Initialize row count

    // Add new row in the table
    $("#add_row").click(function() {
      rowCount++; // Increment row count
      var newRow = `
        <tr id="row_${rowCount}">
          <td>
            <select class="form-control select_group product" data-row-id="${rowCount}" id="product_${rowCount}" name="product[]" required>
              <option value="">Select a medicine</option>
              <?php foreach ($medicines as $k => $v): ?>
                <?php
                $quantityData = $this->model_medicines->getMedicineQuantity($v['id']);
                $quantity = isset($quantityData['qty']) ? $quantityData['qty'] : 0; // Access the 'qty' key
                ?>
                <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] . ' (' . $quantity . ')' ?></option>
              <?php endforeach ?>
            </select>
          </td>
          <td><input type="text" name="available_qty[]" id="available_qty_${rowCount}" class="form-control" disabled></td>
          <td><input type="text" name="qty[]" id="qty_${rowCount}" class="form-control" required></td>
          <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow('${rowCount}')"><i class="fa fa-close"></i></button>
          </td>
        </tr>
      `;
      $("#product_info_table tbody").append(newRow); // Append new row to the table
    });

    // Event delegation for product change
    $(document).on('change', '.product', function() {
      var row_id = $(this).data('row-id');
      var id = $(this).find("option:selected").val();
      // Check if the row_id is valid
      if (id) {
        getMedicineQuantity(row_id, id); // Call the new function
      } else {
        console.error("Invalid row ID");
      }
    });

    // Event delegation for qty change
    // $(document).on('input', 'input[name="qty[]"]', function() {
    //   var row_id = $(this).closest('tr').attr('id').split('_')[1]; // Get the row ID
    //   var qty = Number($(this).val()); // Get the new quantity
    //   var available_qty = Number($("#available_qty_" + row_id).val()); // Get the available quantity

    //   // Calculate the new available quantity
    //   var new_available_qty = available_qty - qty;

    //   // Update the available quantity field
    //   $("#available_qty_" + row_id).val(new_available_qty >= 0 ? new_available_qty : 0); // Ensure it doesn't go below 0

    //   // Call getMedicineQuantity to update available quantity if qty is cleared
    //   if ($(this).val() === "") {
    //     var product_id = $("#product_" + row_id).val(); // Get the selected product ID
    //     if (product_id) {
    //       getMedicineQuantity(row_id, product_id); // Update available quantity based on selected product
    //     }
    //   }
    // });

    // Define the getMedicineQuantity function
    function getMedicineQuantity(row_id, id) {
      if (id) {
        $.ajax({
          url: base_url + 'Controller_Medicines/getMedicineQuantitys', // Adjust the URL as needed
          type: 'POST',
          data: {
            id: id
          },
          dataType: 'json',
          success: function(response) {
            if (response) {
              if (response.qty > 0) { 
                $("#available_qty_" + row_id).val(response.qty); // Set the available quantity
              } else {
                $("#available_qty_" + row_id).val(0); // Clear the available quantity if no product is selected
              }
            }
          },
          error: function() {
            alert('Error in AJAX request');
          }
        });
      } else {
        $("#available_qty_" + row_id).val(''); // Clear the available quantity if no product is selected
      }
    }

    // Call getMedicineQuantity for existing rows on page load
    $(document).ready(function() {
      $('#product_info_table tbody tr').each(function() {
        var row_id = $(this).attr('id').split('_')[1]; // Get the row ID
        var product_id = $("#product_" + row_id).val(); // Get the selected product ID
        if (product_id) {
          getMedicineQuantity(row_id, product_id); // Call the function to set available quantity
        }
      });
    });

    // Add this line to initialize Select2 for all select elements
    $(".select2").select2(); // Initialize Select2 for all select elements

  }); // /document

  
  // Function to remove a row
  function removeRow(row_id) {
    $("#product_info_table tbody tr#row_" + row_id).remove();
    subAmount();
  }
</script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>