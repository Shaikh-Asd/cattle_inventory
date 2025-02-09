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
      Add New

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
          <form role="form" action="<?php base_url('users/create') ?>" method="post" enctype="multipart/form-data">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <!-- <div class="form-group">

                  <label for="product_image">Image</label>
                  <div class="kv-avatar">
                      <div class="file-loading">
                          <input id="product_image" name="product_image" type="file">
                      </div>
                  </div>
                </div> -->
              <!-- <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" autocomplete="off"/> -->
              <!-- <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" autocomplete="off"/> -->

              <div class="form-group">
                <label for="product_name">Inward By</label>
                <select class="form-control" id="customers" name="customers">
                  <option value="">Select a user</option>
                  <?php foreach ($customers as $k => $v): ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>

              <!-- Add a button to add more products -->
              <button type="button" id="addProduct" class="btn btn-info">+</button>

              <table class="table">
                <thead>
                  <tr>
                    <th>Medicine</th>
                    <th>Qty</th>
                    <!-- <th>Rate</th> -->
                    <!-- <th>Amount</th> -->
                    <th>Action</th>
                  </tr>
                </thead>

                <tbody id="productFields">
                  <tr class="product-entry">

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
                    <!-- <td>
                      <input type="text" class="form-control" name="price[]" placeholder="Enter price" autocomplete="off" /> 
                    </td>-->
                    <!--<td>
                      <input type="text" class="form-control" name="amount[]" placeholder="Amount" readonly /> 
                    </td>-->
                    <td>
                      <button type="button" class="btn btn-danger removeProduct">−</button>

                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- <div class="form-group"> -->
              <!-- <label for="sku">SKU</label> -->
              <!-- <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter sku" autocomplete="off" /> -->
              <!-- </div> -->
              <div class="form-group">
                <label for="store">Availability</label>
                <select class="form-control" id="availability" name="availability">
                  <option value="1">Yes</option>
                  <option value="2">No</option>
                </select>
              </div>

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

<script type="text/javascript">
  $(document).ready(function() {
    $(".select_group").select2();
    $("#description").wysihtml5();

    $("#mainProductNav").addClass('active');
    $("#addProductNav").addClass('active');

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

    // Add this script to handle adding and removing product fields
    $("#addProduct").click(function() {
      var newProductEntry = `
        <tr class="product-entry">
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
          <!--<td>
            <input type="text" class="form-control" name="price[]" placeholder="Enter price" autocomplete="off" />
          </td>
          <td>
            <input type="text" class="form-control" name="amount[]" placeholder="Amount" readonly /> 
          </td>-->
          <td>
            <button type="button" class="btn btn-danger removeProduct">−</button>

          </td>
        </tr>`;
      $("#productFields").append(newProductEntry);
    });

    // Remove product entry
    $(document).on('click', '.removeProduct', function() {
      $(this).closest('tr').remove();
    });

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