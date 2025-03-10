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
            <div style="color: red">
              <?php echo validation_errors(); ?>
            </div>

            <div class="row" style="display: flex; align-items: center;">
              <div class="col-lg-3">
                <div class="form-group">
                  <label for="customers">Vendor Name</label>
                  <select class="form-control select2" id="customers" name="customers">
                    <option value="">Select a user</option>
                    <?php foreach ($customers as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
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
              <tbody id="productFields">
                <tr class="product-entry">
                  <td>
                    <select class="form-control select2" name="product_name[]">
                      <option value="">Select a medicine</option>
                      <?php foreach ($medicines as $medicine): ?>
                        <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="qty[]" placeholder="Enter Quantity" autocomplete="off" />
                  </td>
                  <td>
                    <button type="button" class="btn btn-success addProduct">+</button>
                    <button type="button" class="btn btn-danger removeProduct">−</button>
                  </td>
                </tr>
              </tbody>
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
    $(".select2").select2();

    // Add this script to handle adding and removing product fields
    $(document).on('click', '.addProduct', function() {
      var newProductEntry = `
        <tr class="product-entry">
          <td>
            <select class="form-control select2" name="product_name[]">
              <option value="">Select a medicine</option>
              <?php foreach ($medicines as $medicine): ?>
                <option value="<?= $medicine->id; ?>"><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?>)</option>
              <?php endforeach; ?>
            </select>
          </td>
          <td>
            <input type="text" class="form-control" name="qty[]" placeholder="Enter Quantity" autocomplete="off" />
          </td>
          <td>
            <button type="button" class="btn btn-success addProduct">+</button>
            <button type="button" class="btn btn-danger removeProduct">−</button>
          </td>
        </tr>`;
      $("#productFields").append(newProductEntry);

      // Re-initialize Select2 for the newly added select elements
      $(".select2").select2();
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
  });
</script>