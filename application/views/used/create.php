<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
  var $j = jQuery.noConflict(true);
</script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Used Medicines

    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Used Medicines</li>
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
          <form role="form" action="<?php echo base_url('Controller_Used/create') ?>" method="post" enctype="multipart/form-data">
            <div class="box-body">

              <?php echo validation_errors(); ?>


              <div class="form-group">
                <label for="customers">User Name</label>
                <select class="form-control select2" id="used_by" name="used_by" required>
                  <option value="">Select a user</option>
                  <?php foreach ($customers as $k => $v): ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>

              <!-- Add a button to add more products -->
              <button type="button" id="addUsed" class="btn btn-info" aria-label="Add more products">+</button>

              <table class="table">
                <thead>
                  <tr>
                    <th>Medicine</th>
                    <th>Qty</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="usedFields">
                  <tr class="used-entry">
                    <td>
                      <select class="form-control select2 product" data-row-id="1" id="product_1" name="medicine_used[]" required>
                        <!-- <select class="form-control select2 product" data-row-id="1" id="product_1" name="product[]" required onchange="medicineChanged(this)"> -->
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
                    <td>
                      <input type="text" class="form-control" name="qty[]" placeholder="Enter Qty" autocomplete="off" />
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger removeProduct" aria-label="Remove product">−</button>
                    </td>
                  </tr>
                </tbody>
              </table>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              <a href="<?php echo base_url('Controller_Used/') ?>" class="btn btn-warning">Back</a>
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
    $(".select2").select2();
    $("#description").wysihtml5();

    $("#usedNav").addClass('active');
    $("#addUsedNav").addClass('active');

    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' +
      'onclick="alert(\'Call your custom code here.\')">' +
      '<i class="glyphicon glyphicon-tag"></i>' +
      '</button>';
    $("#product_image").fileinput({
      overwriteInitial: true,
      maxFileSize: 1500,
      showClose: false,
      showCaption: false,
      browseLabel: '',
      removeLabel: '',
      browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
      removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
      removeTitle: 'Cancel or reset changes',
      elErrorContainer: '#kv-avatar-errors-1',
      msgErrorClass: 'alert alert-block alert-danger',
      // defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Your Avatar">',
      layoutTemplates: {
        main2: '{preview} ' + btnCust + ' {remove} {browse}'
      },
      allowedFileExtensions: ["jpg", "png", "gif"]
    });

    // Initialize Select2 for existing select elements
    $(".select2").select2({
      placeholder: "Select a medicine",
      allowClear: true
    });

    // Add this script to handle adding and removing product fields
    $("#addUsed").click(function() {
      var newProductEntry = `
        <tr class="used-entry">
          <td>
           <select class="form-control select2 product" data-row-id="1" id="product_1" name="medicine_used[]" required onchange="medicineChanged(this)">
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
          <td>
            <input type="text" class="form-control" name="qty[]" placeholder="Enter Qty" autocomplete="off" />
          </td>
          <td>
            <button type="button" class="btn btn-danger removeProduct" aria-label="Remove product">−</button>
          </td>
        </tr>`;
      $("#usedFields").append(newProductEntry);

      // Re-initialize Select2 for the newly added select elements
      $(".select2").select2({
        placeholder: "Select a medicine",
        allowClear: true
      });
    });

    // Remove product entry
    $(document).on('click', '.removeProduct', function() {
      $(this).closest('tr').remove();
    });

    $(document).on('change', '.product', function() {
      var row_id = $(this).data('row-id');
      var id = $(this).find("option:selected").val();
      console.log(row_id);
      console.log(id);
      // Check if the row_id is valid
      if (id) {
        // getUserMedicineQuantity(row_id, id); // Call the new function
      } else {
        console.error("Invalid row ID");
      }
    });

    // Add this function to handle medicine selection change
    // window.medicineChanged = function(selectElement) {

    //   var medicineId = selectElement.value; // Get the selected medicine ID
    //   if (medicineId) {
    //     alert("Selected Medicine ID: " + medicineId); // Alert the selected ID
    //   } else {
    //     alert("No medicine selected.");
    //   }
    // };

    // Define the getMedicineQuantity function
    // function getUserMedicineQuantity(row_id, id) {
    //   if (id) {
    //     $.ajax({
    //       url: base_url + 'Controller_Used/getUserMedicineQuantity', // Adjust the URL as needed
    //       type: 'POST',
    //       data: {
    //         id: id
    //       },
    //       dataType: 'json',
    //       success: function(response) {
    //         if (response) {
    //           if (response.qty > 0) {
    //             $("#available_qty_" + row_id).val(response.qty); // Set the available quantity
    //           } else {
    //             $("#available_qty_" + row_id).val(0); // Clear the available quantity if no product is selected
    //           }
    //         }
    //       },
    //       error: function() {
    //         alert('Error in AJAX request');
    //       }
    //     });
    //   } else {
    //     $("#available_qty_" + row_id).val(''); // Clear the available quantity if no product is selected
    //   }
    // }

  });
</script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>