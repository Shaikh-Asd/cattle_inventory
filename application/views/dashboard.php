<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<style>
  .negative-stock { color: red; }
.low-stock { color: orange; }
.in-stock { color: green; }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard

    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <?php if ($is_admin == true): ?>

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo count($total_customers) ?></h3>
              <h4><b>Total Customers</b></h4>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="<?php echo base_url('Controller_Customer/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?php echo count($total_medicines) ?></h3>
              <h4><b>Total Medicines</b></h4>
            </div>
            <div class="icon">
              <i class="fa fa-medkit"></i>
            </div>
            <a href="<?php echo base_url('Controller_Medicines/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $total_products ?></h3>

              <h4><b>Total Inward</b></h4>
            </div>
            <div class="icon">
              <i class="fa fa-cube"></i>
            </div>
            <a href="<?php echo base_url('Controller_Products/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $total_orders ?></h3>

              <h4><b>Total Outward</b></h4>
            </div>
            <div class="icon">
              <i class="fa fa-cart-arrow-down"></i>


            </div>
            <a href="<?php echo base_url('Controller_Orders/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- <div class="row">
        <table border="1">
          <tr>
              <th>Transaction Created</th>
              <th>Last Updated</th>
              <th>Customer</th>
              <th>Medicine</th>
              <th>Quantity Given</th>
              <th>Quantity Used</th>
              <th>Quantity Returned</th>
              <th>Balance Quantity</th>
              <th>Actions</th>
          </tr>
          <?php foreach ($transactions as $transaction): ?>
              <tr>
                  <td><?= date('Y-m-d H:i:s', strtotime($transaction->transaction_date)); ?></td>
                  <td><?= date('Y-m-d H:i:s', strtotime($transaction->updated_at)); ?></td>
                  <td><?= $transaction->customer_name; ?></td>
                  <td><?= $transaction->medicine_name; ?></td>
                  <td><?= $transaction->quantity_given; ?></td>
                  <td><?= $transaction->quantity_used; ?></td>
                  <td><?= $transaction->quantity_returned; ?></td>
                  <td><?= $transaction->balance_quantity; ?></td>
                  <td>
                      <a href="<?= base_url('MedicineController/edit_transaction/'.$transaction->transaction_id) ?>">Edit</a>
                  </td>
              </tr>
          <?php endforeach; ?>
      </table> -->
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="box">
            <div class="box-body">
                <div class="col-lg-12">
                    <h3>Medicines Stock</h3>
                    <table id="medicineStockTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
                            <th>Medicine</th>
                            <th>Stock Quantity</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                      <tbody id="medicineStockTableBody">
        
                      </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="row" >
        <div class="col-lg-12">
          <div class="box">
            <div class="box-body">
                <div class="col-lg-12">
                    <h3>Top Customers With Products</h3>
                    <table id="TopCustomersWithProductsTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
                            <th>Custoemr Name</th>
                            <th>Medicine Name</th>
                            <th>Total Ordered</th>
                          </tr>
                        </thead>
                      <tbody id="TopCustomersWithProductsTableBody">
        
                      </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div> -->

      <div class="row">
        <div class="col-lg-12">
          <div class="box">
            <div class="box-body">
              
              <div class="col-lg-6">
                <h3>Outward Medicines</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>

                      <th>Customer Name</th>
                      <th>Medicine Name</th>
                      <th>Stock Outward</th>
                    </tr>
  
  

                  </thead>
                  <tbody id="givenMedicineTableBody">
                  `
                  </tbody>
                </table>
              </div>
  
              <div class="col-lg-6">
            
                  <h3>Inward Medicines</h3>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Customer Name</th>
                        <th>Medicine Name</th>
                        <th>Stock Inward  </th>
                      </tr>
  
                    </thead>
                    <tbody  id="countTotalmedicineTakenTableBody">
                     
                    </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-12">
            <div class="box">
              <div class="box-body">
                <div class="col-lg-12">
                  <h2>Customer-wise Data</h2>
                  <!-- New dropdown for user selection -->
                  <div class="col-lg-3 col-xs-6">
                    <label for="userSelect">Select Customer:</label>
                    <select id="userSelect" class="form-control">
                      <option value="">-- Select Customer --</option>
                      <?php foreach ($total_customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <!-- End of dropdown -->
                    <div class="col-lg-12 mt-2" style="margin-top: 5px;">
                    <table id="userMedicineStatsTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Created At</th>
                          </tr>
                        </thead>
                      <tbody id="userMedicineStatsTableBody">
        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
        <a href="<?= base_url('ReportsController/generate_customer_report/9') ?>">Download PDF</a>

        </div>
        <?php if (!empty($low_stock_medicines)): ?>
            <div style="color: red;">
                <h3>⚠️ Low Stock Alert</h3>
                <ul>
                    <?php foreach ($low_stock_medicines as $medicine): ?>
                        <li><?= $medicine->name; ?> (Stock: <?= $medicine->stock; ?> left)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

      </div>


      <!-- <div class="row">
        <div class="col-lg-6">
            <div class="box">
              <div class="box-body">
                <div class="col-lg-12">
                  <h2>Most Ordered Medicine</h2>
                    <div class="col-lg-12 mt-2" style="margin-top: 5px;">
                    <table id="mostOrderedProductTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
                            <th>Medicine</th>
                            <th>Order Count</th>
                          </tr>
                        </thead>
                      <tbody id="mostOrderedProductTableBody">
        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="box">
              <div class="box-body">
                <div class="col-lg-12">
                  <h2>Most Ordered Medicine By Quantity</h2>
                    <div class="col-lg-12 mt-2" style="margin-top: 5px;">
                    <table id="MostOrderedProductByQuantityTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
                            <th>Medicine</th>
                            <th>Order Quantity</th>
                          </tr>
                        </thead>
                      <tbody id="MostOrderedProductByQuantityTableBody">
        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div> -->

        <!-- ./col -->
        <!-- <div class="col-lg-3 col-xs-6">
          small box
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $total_paid_orders ?></h3>

              <h4><b>Total Paid Orders</b></h4>
            </div>
            <div class="icon">
              <i class="fa fa-dollar"></i>
            </div>
            <a href="<?php echo base_url('orders/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div> -->



        <!-- <div class="col-lg-3 col-xs-6">
            small box
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php echo $total_category ?></h3>

                <h4><b>Total Category</b></h4>
              </div>
              <div class="icon">
                <i class="fa fa-cubes"></i>
              </div>
              <a href="<?php echo base_url('category/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
        <!-- ./col -->
        <!-- <div class="col-lg-3 col-xs-6">
            small box
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php echo $total_attribures; ?></h3>

               <h4><b>Total Elements</h4></b>
              </div>
              <div class="icon">
                <i class="fa fa-files-o"></i>
              </div>
              <a href="<?php echo base_url('attributes/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
        <!-- ./col -->
        <!-- <div class="col-lg-3 col-xs-6">
            small box
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?php echo '1' ?></h3>

                <h4><b>Company</b></h4>
              </div>
              <div class="icon">
                <i class="ion ion-android-home"></i>
              </div>
              <a href="<?php echo base_url('company/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
        <!-- ./col -->
      <!-- /.row -->
    <?php endif; ?>

       <!-- End of new section -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#userSelect").change(function() {
      var userId = $(this).val();  // Get the selected user ID from the dropdown

      if (userId) {
          $.ajax({
              url: "<?php echo base_url('dashboard/getUserMedicineStats/'); ?>" + userId,
              method: "GET",
              success: function(data) {
                  // Assuming the response 'data' is in JSON format and contains user medicine stats
                  var response = JSON.parse(data); 
              
                  // Example: Displaying the data in a table
                  var output = '';
                  var i = 1 ;

                  if (response && response.length > 0) {
                    
                    $.each(response, function(index, row) {
                      var date = new Date(row.created_at);
                      var formattedDate = date.toLocaleDateString('en-GB', { 
                            day: '2-digit', 
                            month: 'long', 
                            year: 'numeric' 
                        });

                        output += '<tr>';
                        output += '<td>' + i + '</td>';
                        output += '<td>' + row.name + '</td>';
                        output += '<td>' + row.total_quantity_ordered  + '</td>';
                        output += '<td>' + formattedDate  + '</td>';
                        output += '</tr>';
                        i++;
                    });

                    $('#userMedicineStatsTableBody').html(output);
                  }else{
                    $('#userMedicineStatsTableBody').html('<tr><td colspan="3">No data found.</td></tr>');
                  }
              },
              error: function() {
                  alert('Error retrieving data!');
              }
          });
      } else {
          // If no user is selected, you can display a message or hide the container
          $('#userMedicineStatsTableBody').html('<p>Please select a user.</p>');
      }
  });

  manageTable = $('#userMedicineStatsTable').DataTable({
    dom: 'Bfrtip',
    buttons: [
      'copy', 'csv', 'excel', 'print'
    ],
    'order': []
  });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dashboardMainMenu").addClass('active');

    //Given Medicine
    $.ajax({
        url: "<?php echo base_url('dashboard/countTotalmedineGiven/'); ?>",
        method: "GET",
        success: function(data) {
            var response = JSON.parse(data); 
            
            var output = '';
            var i = 1 ;

            if (response) {
          
              var tableRows = '';
                        
              $.each(response, function(index, item) {
                  tableRows += `
                                <tr>  
                                  <td>${item.customer_name}</td>
                                  <td>${item.medicine_name}</td>
                                  <td>${item.qty}</td>
                                </tr>
                  `;
              });
              $('#givenMedicineTableBody').html(tableRows);
            }else{
              $('#givenMedicineTableBody').html('<tr><td colspan="3">No data found.</td></tr>');
            }
        },
        error: function() {
            alert('Error retrieving data!');
        }
    });

    //Taken Medicine
    $.ajax({
        url: "<?php echo base_url('dashboard/countTotalmedicineTaken/'); ?>",
        method: "GET",
        success: function(data) {
            var response = JSON.parse(data); 
            
            var output = '';
            var i = 1 ;
            console.log(response);

            if (response) {
          
              var tableRows = '';
                        
              $.each(response, function(index, item) {
                  tableRows += `
                                <tr>  
                                  <td>${item.customer_name}</td>
                                  <td>${item.medicine_name}</td>
                                  <td>${item.qty}</td>
                                </tr>
                  `;
              });
              $('#countTotalmedicineTakenTableBody').html(tableRows);
            }else{
              $('#countTotalmedicineTakenTableBody').html('<tr><td colspan="3">No data found.</td></tr>');
            }
        },
        error: function() {
            alert('Error retrieving data!');
        }
    });
    
    //most_ordered_product
    // $.ajax({
    //     url: "<?php echo base_url('dashboard/most_ordered_product/'); ?>",
    //     method: "GET",
    //     success: function(data) {
    //         var response = JSON.parse(data); 
            
    //         var output = '';
    //         var i = 1 ;

    //         if (response) {
          
    //           var tableRows = '';
                        
    //           $.each(response, function(index, product) {
    //               tableRows += `
    //                   <tr>
    //                       <td>${i}</td>
    //                       <td>${product.name}</td>
    //                       <td>${product.order_count}</td>
    //                   </tr>
    //               `;
    //           });
    //           $('#mostOrderedProductTableBody').html(tableRows);
    //         }else{
    //           $('#mostOrderedProductTableBody').html('<tr><td colspan="3">No data found.</td></tr>');
    //         }
    //     },
    //     error: function() {
    //         alert('Error retrieving data!');
    //     }
    // });

    //getMostOrderedProductByQuantity
    // $.ajax({
    //     url: "<?php echo base_url('dashboard/getMostOrderedProductByQuantity/'); ?>",
    //     method: "GET",
    //     success: function(data) {
    //         var response = JSON.parse(data); 
            
    //         var output = '';
    //         var i = 1 ;

    //         if (response) {
          
    //           var tableRows = '';
                        
    //           $.each(response, function(index, product) {
    //               tableRows += `
    //                   <tr>
    //                       <td>${i}</td>
    //                       <td>${product.name}</td>
    //                       <td>${product.total_quantity}</td>
    //                   </tr>
    //               `;
    //           });
    //           $('#MostOrderedProductByQuantityTableBody').html(tableRows);
    //         }else{
    //           $('#MostOrderedProductByQuantityTableBody').html('<tr><td colspan="3">No data found.</td></tr>');
    //         }
    //     },
    //     error: function() {
    //         alert('Error retrieving data!');
    //     }
    // });

    // getMedicineStock

    $.ajax({
        url: "<?php echo base_url('dashboard/getMedicineStock/'); ?>",
        method: "GET",
        success: function(data) {
            var response = JSON.parse(data); 
            var output = '';
            var i = 1 ;

            if (response) {
          
              var tableRows = '';
              const lowStockThreshold = 10;
              $.each(response, function(index, medicine) {
                let stockStatus = '';
                if (medicine.qty < 0) {
                    stockStatus = 'Out of Stock';
                    stockStatusClass = 'negative-stock';  
                } else if (medicine.qty <= lowStockThreshold) {
                    stockStatus = 'Low Stock';
                    stockStatusClass = 'low-stock';  
                } else {
                    stockStatus = 'In Stock';
                    stockStatusClass = 'in-stock'; 
                }
                  tableRows += `
                      <tr>
                          <td>${i}</td>
                          <td>${medicine.name}</td>
                          <td>${medicine.qty}</td>
                          <td class="${stockStatusClass}">${stockStatus}</td>
                      </tr>
                  `;
                  i++;
              });
              $('#medicineStockTableBody').html(tableRows);
            }else{
              $('#medicineStockTableBody').html('<tr><td colspan="3">No data found.</td></tr>');
            }
        },
        error: function() {
            alert('Error retrieving data!');
        }
    });
 
    //TopCustomersWithProducts
    // $.ajax({
    //     url: "<?php echo base_url('dashboard/getTopCustomersWithProducts/'); ?>",
    //     method: "GET",
    //     success: function(data) {
    //         var response = JSON.parse(data); 
    //         var output = '';
    //         var i = 1 ;

    //         if (response) {
          
    //           var tableRows = '';
              
    //           $.each(response, function(index, product) {
    //               tableRows += `
    //                   <tr>
    //                       <td>${i}</td>
    //                       <td>${product.customer_name}</td>
    //                       <td>${product.medicine_name}</td>
    //                       <td>${product.total_ordered}</td>
    //                   </tr>
    //               `;
    //           });
    //           $('#TopCustomersWithProductsTableBody').html(tableRows);
    //         }else{
    //           $('#TopCustomersWithProductsTableBody').html('<tr><td colspan="3">No data found.</td></tr>');
              
    //         }
    //     },
    //     error: function() {
    //         alert('Error retrieving data!');
    //     }
    // });
  });
</script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<!-- Update for DataTables initialization to include search and pagination -->
<script type="text/javascript">
  $(document).ready(function() {
    // Initialize DataTables with search and pagination
    if (!$.fn.DataTable.isDataTable('#medicineStockTable')) {
      $('#medicineStockTable').DataTable({
        responsive: true, // Make the table responsive
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'print'
        ],
        'order': [],
        'paging': true,
        'searching': true
      });
    }

    if (!$.fn.DataTable.isDataTable('#TopCustomersWithProductsTable')) {
      $('#TopCustomersWithProductsTable').DataTable({
        responsive: true, // Make the table responsive
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'print'
        ],
        'order': [],
        'paging': true,
        'searching': true
      });
    }

    if (!$.fn.DataTable.isDataTable('#userMedicineStatsTable')) {
      $('#userMedicineStatsTable').DataTable({
        responsive: true, // Make the table responsive
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'print'
        ],
        'order': [],
        'paging': true,
        'searching': true
      });
    }

    if (!$.fn.DataTable.isDataTable('#mostOrderedProductTable')) {
      $('#mostOrderedProductTable').DataTable({
        responsive: true, // Make the table responsive
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'print'
        ],
        'order': [],
        'paging': true,
        'searching': true
      });
    }

    if (!$.fn.DataTable.isDataTable('#MostOrderedProductByQuantityTable')) {
      $('#MostOrderedProductByQuantityTable').DataTable({
        responsive: true, // Make the table responsive
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'print'
        ],
        'order': [],
        'paging': true,
        'searching': true
      });
    }
  });
</script>

<!-- Ensure sidebar collapse functionality is working -->
<script type="text/javascript">
  $(document).ready(function() {
    // Add click event for sidebar toggle
    $('.sidebar-toggle').click(function() {
      $('.main-sidebar').toggleClass('collapsed'); // Toggle the collapsed class
    });
  });
</script>
