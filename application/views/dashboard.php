<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


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
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo count($total_customers) ?></h3>
              <h4><b>Total Customers</b></h4>
            </div>
            <div class="icon">

              <i class="fa fa-dollar"></i>
            </div>
            <a href="<?php echo base_url('customers/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo count($total_medicines) ?></h3>
              <h4><b>Total Medicines</b></h4>
            </div>
            <div class="icon">
              <i class="fa fa-dollar"></i>
            </div>
            <a href="<?php echo base_url('medicines/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $total_products ?></h3>

              <h4><b>Total Taken</b></h4>
            </div>
            <div class="icon">
              <i class="fa fa-cube"></i>
            </div>
            <a href="<?php echo base_url('products/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $total_brands ?></h3>

              <h4><b>Total Given</b></h4>
            </div>
            <div class="icon">
              <i class="fa fa-cart-arrow-down"></i>

            </div>
            <a href="<?php echo base_url('brands/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-12">
          <h3>User-wise Data</h3>
          <div id="userMedicineStats"></div>
        </div>
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

        <script type="text/javascript">
          $(document).ready(function() {
            $("#userSelect").change(function() {
              var userId = $(this).val();
              if (userId) {
                $.ajax({
                  url: "<?php echo base_url('dashboard/getUserMedicineStats/'); ?>" + userId,
                  method: "GET",
                  success: function(data) {
                    $("#userMedicineStats").html(data);
                  }
                });
              } else {
                $("#userMedicineStats").html('');
              }
            });
          });
        </script>
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
      </div>
      <!-- /.row -->
    <?php endif; ?>

    <!-- New section for customer medicine stock statistics -->
    <div class="row">
      <div class="col-lg-12">
        <h3>Taken Medicine Stock</h3>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Customer Name</th>
              <th>Medicine Name</th>
              <th>Stock Taken</th>
            </tr>

          </thead>
          <tbody>
            <?php
            // Query to fetch stock statistics data with customer and medicine names
            $stock_data = $this->model_products->countTotalmedineTaken(); // This will now include names
            foreach ($stock_data as $item) {
              echo "<tr>  
                        <td>{$item['customer_name']}</td>
                        <td>{$item['medicine_name']}</td>
                        <td>{$item['qty']}</td>
                      </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <h3>Given Medicine Stock</h3>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Customer Name</th>
              <th>Medicine Name</th>
              <th>Stock Given</th>
            </tr>


          </thead>
          <tbody>
            <?php
            // Query to fetch stock statistics data with customer and medicine names
            $stock_data = $this->model_orders->countTotalmedineGiven(); // This will now include names
            foreach ($stock_data as $item) {
              echo "<tr>  
                        <td>{$item['customer_name']}</td>
                        <td>{$item['medicine_name']}</td>
                        <td>{$item['qty']}</td>
                      </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- End of new section -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#dashboardMainMenu").addClass('active');
  });
</script>