<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage Users


    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Users</li>
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

        <?php //if(in_array('createGroup', $user_permission)): 
        ?>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add User</button>
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
                  <th>User Name</th>
                  <th>Status</th>
                  <?php //if(in_array('updateGroup', $user_permission) || in_array('deleteGroup', $user_permission)): 
                  ?>
                  <th>User Type</th>
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
        <h4 class="modal-title">Add User</h4>
      </div>


      <form role="form" action="<?php echo base_url('Controller_Customer/create') ?>" method="post" id="createForm">

        <div class="modal-body">

          <div class="form-group">
            <label for="brand_name">User Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter user name" autocomplete="off" value="<?php echo set_value('customer_name'); ?>">
          </div>
          <div class="form-group">
            <label for="brand_name">User Type</label>
            <!-- <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter user name" autocomplete="off" value="<?php echo set_value('customer_name'); ?>"> -->
            <select class="form-control" id="user_type" name="user_type">
              <option value="1">User</option>
              <option value="2">Vendor</option>
            </select>
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
        <h4 class="modal-title">Edit User</h4>
      </div>


        <form role="form" action="<?php echo base_url(uri: 'Controller_Customer/update/') ?>" method="post" id="updateForm">
        <input type="hidden" id="customer_id" name="customer_id" value="">
        <div class="modal-body">
          <div id="messages"></div>

          <div class="form-group">
            <label for="edit_customer_name">Customer Name</label>
            <input type="text" class="form-control" id="edit_customer_name" name="edit_customer_name" placeholder="Enter customer name" autocomplete="off">
          </div>

          <div class="form-group">
            <label for="edit_user_type">User Type</label>
            <select class="form-control" id="edit_user_type" name="edit_user_type">
              <option value="1">User</option>
              <option value="2">Vendor</option>
            </select>
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
          <button type="submit" class="btn btn-primary">Save changes</button>
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
        <h4 class="modal-title">Remove Customer</h4>
      </div>

      <form role="form" action="<?php echo base_url('Controller_Customer/remove') ?>" method="post" id="removeForm">
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
    $("#customerNav").addClass('active');

    // initialize the datatable 
    manageTable = $('#manageTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'print'
      ],
      'ajax': {
        url: base_url + 'Controller_Customer/fetchCustomerData',
        type: 'GET',
        dataSrc: function(json) {
          console.log(json); // Log the entire response for debugging
          return json.data.map(function(item) {
            return {
              id: item[0],
              name: item[2],
              status: item[3],
              user: item[4],
              action: '<button type="button" class="btn btn-warning btn-sm" onclick="editFunc(' + item[1] + ')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>' +
                '<button type="button" class="btn btn-danger btn-sm" onclick="removeFunc(' + item[1] + ')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>'
            };
          });
        }
      },
      'order': [],
      'columns': [{
          data: 'id'
        },
        {
          data: 'name'
        },
        {
          data: 'status'
        },
        {
          data: 'user'
        },
        {
          data: 'action'
        } // Ensure this matches the new action column
      ]
    });

    // submit the create from 
    $("#createForm").unbind('submit').on('submit', function() {
      var form = $(this);
      // Log the form data for debugging
      console.log(form.serialize());
      // remove the text-danger
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: form.serialize(), // /converting the form data into array and sending it to server
        dataType: 'json',
        success: function(response) {

          manageTable.ajax.reload(null, false);

          if (response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
              '</div>');


            //))))))}) hide the modal
            $("#addModal").modal('hide').on('hidden.bs.modal', function() {
              console.log("Modal closed successfully.");
            }).on('error', function() {
              console.error("Error closing modal.");
            });

            // reset the form
            $("#createForm")[0].reset();
            $("#createForm .form-group").removeClass('has-error').removeClass('has-success');

          } else {

            if (response.messages instanceof Object) {
              $.each(response.messages, function(index, value) {
                var id = $("#" + index);

                id.closest('.form-group')
                  .removeClass('has-error')
                  .removeClass('has-success')
                  .addClass(value.length > 0 ? 'has-error' : 'has-success');

                id.after(value);

              });
            } else {
              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                '</div>');
            }
          }
        }
      });

      return false;
    });


  });

  // edit function
  function editFunc(id) {
    $.ajax({
      url: base_url + 'Controller_Customer/fetchCustomerDataById/' + id, // Ensure this URL is correct
      type: 'post',
      dataType: 'json',
      success: function(response) {
        // Check if the response is an object and has the expected properties
        if (response && response.id) { // Check if the response object has data
          $("#edit_customer_name").val(response.name); // Populate the name field
          $("#edit_active").val(response.active); // Populate the status field
          $("#edit_user_type").val(response.user_type); // Populate the user type dropdown
          
          // Set the customer_id in a hidden input field
          $("#customer_id").val(response.id);

          $("#editModal").modal('show'); // Show the modal after populating data
        } else {
          console.error("No data returned for ID:", id);
        }
      },
      error: function(xhr, status, error) {
        console.error("AJAX Error:", status, error); // Log any AJAX errors
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
            customer_id: id
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