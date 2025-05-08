<!-- jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>

<!-- jQuery UI -->
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Bootstrap -->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- SweetAlert -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage Medicines

    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Medicines</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <?php //if(in_array('createGroup', $user_permission)): 
         ?>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Medicine</button>
        <br /> <br />
        <?php //endif; 
         ?>

        <div class="box">

          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Sr no</th>
                  <th>Medicine Name</th>
                  <th>Dead Stock</th>
                  <th>Status</th>
                  <?php //if(in_array('updateGroup', $user_permission) || in_array('deleteGroup', $user_permission)): 
                   ?>

                  <th>Action</th>
                  <?php //endif; 
                   ?>
                </tr>
              </thead>

            </table>
          </div>
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


<!-- create brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Medicine</h4>
      </div>

      <form role="form" action="<?php echo base_url('Controller_Medicines/create') ?>" method="post" id="createForm">

        <div class="modal-body">

          <div class="form-group">
            <label for="brand_name">Medicine Name</label>
            <input type="text" class="form-control" id="medicine_name" name="medicine_name" placeholder="Enter medicine name" autocomplete="off" value="<?php echo set_value('medicine_name'); ?>">
          </div>
          <div class="form-group">
            <label for="dead_stock">Dead Stock</label>
            <input type="number" class="form-control" id="dead_stock" name="dead_stock" placeholder="Enter dead stock" autocomplete="off" value="<?php echo set_value('dead_stock'); ?>">
          </div>

          <div class="form-group">
            <label for="active">Status</label>
            <select class="form-control" id="active" name="active">
              <option value="1">Active</option>
              <option value="2">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>

      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- edit brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Medicine</h4>
      </div>

      <form role="form" action="<?php echo base_url('Controller_Medicines/update') ?>" method="post" id="updateForm">

        <div class="modal-body">
          <div id="messages"></div>

          <div class="form-group">
            <label for="edit_medicine_name">Medicine Name</label>
            <input type="text" class="form-control" id="edit_medicine_name" name="edit_medicine_name" placeholder="Enter medicine name" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="edit_dead_stock">Dead Stock</label>
            <input type="number" class="form-control" id="edit_dead_stock" name="edit_dead_stock" placeholder="Enter dead stock" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="edit_active">Status</label>
            <select class="form-control" id="edit_active" name="edit_active">

              <option value="1">Active</option>
              <option value="2">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>

      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Medicine</h4>
      </div>

      <form role="form" action="<?php echo base_url('Controller_Medicines/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script type="text/javascript">
  var manageTable;
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {



    $("#medicineNav").addClass('active');

    // initialize the datatable 
    manageTable = $('#manageTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'print'
      ],
      'ajax': base_url + 'Controller_Medicines/fetchMedicinesData',
      'order': []
    });

    // submit the create from 
    $("#createForm").unbind('submit').on('submit', function(e) {
      e.preventDefault();
      var form = $(this);
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
          manageTable.ajax.reload(null, false);
          if (response.success === true) {
            // First hide the modal
            $("#addModal").modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            // Reset the form
            form[0].reset();

            // Show success message
            setTimeout(function() {
              swal({
                title: "Success!",
                text: response.messages,
                type: "success",
                timer: 2000,
                showConfirmButton: false,
                customClass: 'medium-swal',
                backdrop: false
              });
            }, 200);
          } else {
            if (typeof response.messages === 'object') {
              $.each(response.messages, function(index, value) {
                var id = $("#" + index);
                id.closest('.form-group')
                  .removeClass('has-error')
                  .removeClass('has-success')
                  .addClass(value.length > 0 ? 'has-error' : 'has-success');
                id.after(value);
              });
            } else {
              // Show error message in SweetAlert
              swal({
                title: "Error!",
                text: response.messages,
                type: "error",
                customClass: 'medium-swal',
                backdrop: false
              });
              
              // Keep the modal open so the user can correct the error
              $("#addModal").modal('show');
            }
          }
        },
        error: function(xhr, status, error) {
          swal({
            title: "Error!",
            text: "An error occurred while processing your request.",
            type: "error",
            customClass: 'medium-swal',
            backdrop: false
          });
        }
      });
      return false;
    });

  });

  // edit function
  function editFunc(id) {
    $.ajax({
      url: base_url + 'Controller_Medicines/fetchMedicinesDataById/' + id,
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // Check if response contains the expected data
        console.log("response" + response);
        if (response && response.name && response.active) {
          console.log(response.name);
          $("#edit_medicine_name").val(response.name);
          $("#edit_active").val(response.active);
          $("#edit_dead_stock").val(response.dead_stock);
          
          // Set the medicine ID as a data attribute on the form
          $("#updateForm").data('medicine-id', id);
          
          // Show the edit modal
          $("#editModal").modal('show');
        } else {
          console.error("Invalid response data:", response);
        }

        // submit the edit form 
        $("#updateForm").unbind('submit').bind('submit', function(e) {
          e.preventDefault();
          var form = $(this);
          var medicineId = form.data('medicine-id');
          $(".text-danger").remove();

          $.ajax({
            url: base_url + 'Controller_Medicines/update/' + medicineId,
            type: form.attr('method'),
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
              manageTable.ajax.reload(null, false);

              if (response.success === true) {
                // First hide the modal
                $("#editModal").modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                // After modal is hidden, show the success message
                setTimeout(function() {
                  swal({
                    title: "Success!",
                    text: response.messages,
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: 'medium-swal',
                    backdrop: false
                  });
                }, 200);

                // Reset the form
                $("#updateForm .form-group").removeClass('has-error').removeClass('has-success');
              } else {
                if (typeof response.messages === 'object') {
                  $.each(response.messages, function(index, value) {
                    var id = $("#" + index);
                    id.closest('.form-group')
                      .removeClass('has-error')
                      .removeClass('has-success')
                      .addClass(value.length > 0 ? 'has-error' : 'has-success');
                    id.after(value);
                  });
                } else {
                  // Show error message in SweetAlert
                  swal({
                    title: "Error!",
                    text: response.messages,
                    type: "error",
                    customClass: 'medium-swal',
                    backdrop: false
                  });
                  
                  // Keep the modal open so the user can correct the error
                  $("#editModal").modal('show');
                }
              }
            },
            error: function(xhr, status, error) {
              swal({
                title: "Error!",
                text: "An error occurred while processing your request.",
                type: "error",
                customClass: 'medium-swal',
                backdrop: false
              });
            }
          });
          return false;
        });
      }
    });
  }

  // remove functions 
  function removeFunc(id) {
    if (id) {
      $("#removeForm").on('submit', function() {

        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: {
            medicine_id: id
          },
          dataType: 'json',
          success: function(response) {

            manageTable.ajax.reload(null, false);

            if (response.success === true) {
              $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
                '</div>');

              // hide the modal
              $("#removeModal").modal('hide');

            } else {

              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                '</div>');
            }
          }
        });

        return false;
      });
    }
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