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
      Edit Used Medicines


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
          <form role="form" action="<?php echo base_url('Controller_Used/update/' . $used_data['id']); ?>" method="post" enctype="multipart/form-data">
            <div class="box-body">

              <?php echo validation_errors(); ?>



              <div class="form-group">
                <label for="customers">Used By</label>
                <select class="form-control" id="customers" name="customers">
                  <option value="">Select a user</option>
                  <?php foreach ($customers as $k => $v): ?>


                    <option value="<?php echo $v['id'] ?>" <?php if ($used_data['customer_id'] == $v['id']) {
                                                              echo "selected='selected'";
                                                            } ?>><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>


              <button type="button" id="addUsed" class="btn btn-info">+</button>

              <table class="table">
                <thead>
                  <tr>
                    <th>Medicine</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="usedFields">
                  <?php
                  $medicines_ids = json_decode($used_data['medicine_id']) ?? [];
                  $qtys = json_decode($used_data['qty']) ?? [];
                  $rates = json_decode($used_data['price']) ?? [];

                  // Ensure that all variables are arrays
                  if (!is_array($medicines_ids)) {
                    $medicines_ids = [$medicines_ids]; // Wrap in an array if it's a single value
                  }
                  if (!is_array($qtys)) {
                    $qtys = [$qtys]; // Wrap in an array if it's a single value
                  }
                  if (!is_array($rates)) {
                    $rates = [$rates]; // Wrap in an array if it's a single value
                  }

                  for ($i = 0; $i < count($medicines_ids); $i++): ?>
                    <tr class="used-entry">
                      <td>
                        <select class="form-control" name="product_name[]">
                          <option value="">Select a medicine</option>
                          <?php foreach ($medicines as $k => $v): ?>
                            <option value="<?php echo $v['id'] ?>" <?php if ($medicines_ids[$i] == $v['id']) {
                                                                      echo "selected='selected'";
                                                                    } ?>><?php echo $v['name'] ?></option>
                          <?php endforeach ?>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control" name="qty[]" value="<?php echo isset($qtys[$i]) ? $qtys[$i] : ''; ?>" placeholder="Enter Qty" autocomplete="off" />
                      </td>
                      <td>
                        <input type="text" class="form-control" name="price[]" value="<?php echo isset($rates[$i]) ? $rates[$i] : ''; ?>" placeholder="Enter price" autocomplete="off" />
                      </td>
                      <td>
                        <button type="button" class="btn btn-danger removeProduct">−</button>

                      </td>
                    </tr>
                  <?php endfor; ?>
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
  $(document).ready(function() {
    $(".select_group").select2();
    $("#description").wysihtml5();

    $("#mainUsedNav").addClass('active');
    $("#manageUsedNav").addClass('active');

    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' +
      'onclick="alert(\'Call your custom code here.\')">' +
      '<i class="glyphicon glyphicon-tag"></i>' +
      '</button>';
    

    // Fetch product data by ID when the page loads
    var usedId = <?php echo $used_data['id']; ?>; // Assuming you have the product ID available
    $.ajax({
      url: "<?php echo base_url('Controller_Used/fetchUsedDataById/'); ?>" + usedId,
      type: "GET",
      dataType: "json",
      success: function(data) {
        // Populate the form fields with the fetched data
        $('#customers').val(data.customer_id); // Assuming you have customer_id in the response

        // Populate medicine fields
        var medicines = data.medicine_name.split(','); // Split the medicine names
        var qtys = data.qty;
        var rates = data.price;

        // Clear existing product fields before populating
        $("#usedFields").empty();

        for (var i = 0; i < medicines.length; i++) {
          var newProductEntry = `
            <tr class="product-entry">
              <td>
                <select class="form-control" name="product_name[]">
                  <option value="">Select a medicine</option>
                  <?php foreach ($medicines as $k => $v): ?>
                    <option value="<?php echo $v['id'] ?>" ${medicines[i].trim() === '<?php echo $v['name'] ?>' ? 'selected' : ''}><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>
              </td>
              <td>
                <input type="text" class="form-control" name="qty[]" value="${qtys[i]}" placeholder="Enter Qty" autocomplete="off" />
              </td>
              <td>
                <input type="text" class="form-control" name="price[]" value="${rates[i]}" placeholder="Enter price" autocomplete="off" />
              </td>
              <td>
                <button type="button" class="btn btn-danger removeProduct">−</button>
              </td>
            </tr>`;
          $("#usedFields").append(newProductEntry);
        }
      }
    });

    // Add new product entry
    $("#addUsed").click(function() {
      var newUsedEntry = `
        <tr class="used-entry">
          <td>
            <select class="form-control" name="product_name[]">
              <option value="">Select a medicine</option>
              <?php foreach ($medicines as $k => $v): ?>
                <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
              <?php endforeach ?>
            </select>
          </td>
          <td>
            <input type="text" class="form-control" name="qty[]" placeholder="Enter Qty" autocomplete="off" />
          </td>
          <td>
            <input type="text" class="form-control" name="price[]" placeholder="Enter price" autocomplete="off" />
          </td>
          <td>
            <button type="button" class="btn btn-danger removeProduct">−</button>
          </td>
        </tr>`;
      $("#usedFields").append(newUsedEntry);
    });

    // Remove product entry
    $(document).on('click', '.removeProduct', function() {
      $(this).closest('tr').remove();
    });

    // Calculate amount on rate and qty change
    $(document).on('input', 'input[name="qty[]"], input[name="price[]"]', function() {
      var qty = $(this).closest('tr').find('input[name="qty[]"]').val();
      var rate = $(this).closest('tr').find('input[name="price[]"]').val();
      var amount = qty * rate;
      $(this).closest('tr').find('input[name="amount[]"]').val(amount);
    });

  });
</script>