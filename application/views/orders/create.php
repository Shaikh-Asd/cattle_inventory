<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Given Medicines
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Given Medicines</li>
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
                <label for="taken_by" class="col-sm-2 control-label">Taken By</label>
                <div class="col-sm-10">
                  <select class="form-control" id="taken_by" name="taken_by" required>
                    <option value="">Select a customer</option>
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
                  <button type="button" id="add_row" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Medicine</button>
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
                      <select class="form-control" id="product_1" name="product[]" required>
                        <option value="">Select a medicine</option>
                        <?php foreach ($medicines as $k => $v): ?>
                          <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
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
            <select class="form-control select_group product" data-row-id="row_${rowCount}" id="product_${rowCount}" name="product[]" required>
              <option value=""></option>
              <?php foreach ($medicines as $k => $v): ?>
                <option value="<?php echo $v['id'] ?>" data-qty="<?php echo isset($v['qty']) ? $v['qty'] : 0; ?>" data-price="<?php echo isset($v['price']) ? $v['price'] : 0; ?>">
                  <?php echo $v['name']; ?>
                </option>
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
      console.log("Product changed"); // Check if this logs
      var row_id = $(this).data('row-id');
      console.log("Row ID:", row_id); // Log the row ID
      getMedicineQuantity(row_id); // Call the new function
    });

    // Define the getMedicineQuantity function
    function getMedicineQuantity(row_id) {
      var product_id = $("#product_" + row_id).val();
      if (product_id) {
        $.ajax({
          url: base_url + 'Controller_Orders/getMedicineQuantity', // Adjust the URL as needed
          type: 'POST',
          data: { id: product_id },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              $("#available_qty_" + row_id).val(response.available_qty); // Set the available quantity
              $("#rate_" + row_id).val(response.price); // Set the rate if needed
            } else {
              alert('Error retrieving quantity');
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

  }); // /document

  function getTotal(row = null) {
    if (row) {
      var total = Number($("#rate_" + row).val()) * Number($("#qty_" + row).val());
      total = total.toFixed(2);
      $("#amount_" + row).val(total);
      $("#amount_value_" + row).val(total);


      subAmount();

    } else {
      alert('no row !! please refresh the page');
    }
  }

  // get the product information from the server
  function getProductData(row_id) {
    var product_id = $("#product_" + row_id).val();
    var selectedOption = $("#product_" + row_id + " option:selected");

    if (product_id == "") {
      $("#rate_" + row_id).val("");
      $("#qty_" + row_id).val("");
      $("#amount_" + row_id).val("");
      $("#amount_value_" + row_id).val("");
    } else {
      var qty = selectedOption.data('qty'); // Get quantity from data attribute
      var price = selectedOption.data('price'); // Get price from data attribute

      $("#rate_" + row_id).val(price);
      $("#qty_" + row_id).val(qty); // Set the quantity
      $("#amount_" + row_id).val((qty * price).toFixed(2)); // Calculate amount
      $("#amount_value_" + row_id).val((qty * price).toFixed(2)); // Set hidden amount value

      subAmount(); // Update the subtotal
    }
  }

  // calculate the total amount of the order
  function subAmount() {
    var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value'] : 0; ?>;
    var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value'] : 0; ?>;

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for (x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);

      totalSubAmount = Number(totalSubAmount) + Number($("#amount_" + count).val());
    } // /for

    totalSubAmount = totalSubAmount.toFixed(2);

    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);

    // vat
    var vat = (Number($("#gross_amount").val()) / 100) * vat_charge;
    vat = vat.toFixed(2);
    $("#vat_charge").val(vat);
    $("#vat_charge_value").val(vat);

    // service
    var service = (Number($("#gross_amount").val()) / 100) * service_charge;
    service = service.toFixed(2);
    $("#service_charge").val(service);
    $("#service_charge_value").val(service);

    // total amount
    var totalAmount = (Number(totalSubAmount) + Number(vat) + Number(service));
    totalAmount = totalAmount.toFixed(2);
    // $("#net_amount").val(totalAmount);
    // $("#totalAmountValue").val(totalAmount);

    var discount = $("#discount").val();
    if (discount) {
      var grandTotal = Number(totalAmount) - Number(discount);
      grandTotal = grandTotal.toFixed(2);
      $("#net_amount").val(grandTotal);
      $("#net_amount_value").val(grandTotal);
    } else {
      $("#net_amount").val(totalAmount);
      $("#net_amount_value").val(totalAmount);

    } // /else discount 

  } // /sub total amount

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