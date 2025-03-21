<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">

      <li id="dashboardMainMenu">
        <a href="<?php echo base_url('dashboard') ?>">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>

      <!-- <?php if (in_array('createBrand', $user_permission) || in_array('updateBrand', $user_permission) || in_array('viewBrand', $user_permission) || in_array('deleteBrand', $user_permission)): ?>
        <li id="brandNav">
          <a href="<?php echo base_url('Controller_Items/') ?>">
            <i class="fa fa-cart-arrow-down"></i> <span>Items</span>
          </a>
        </li>
      <?php endif; ?> -->

      <!-- <?php if (in_array('createCategory', $user_permission) || in_array('updateCategory', $user_permission) || in_array('viewCategory', $user_permission) || in_array('deleteCategory', $user_permission)): ?>
            <li id="categoryNav">
              <a href="<?php echo base_url('Controller_Category/') ?>">
                <i class="fa fa-cubes"></i> <span>Category</span>
              </a>
            </li>
          <?php endif; ?>

          <?php if (in_array('createStore', $user_permission) || in_array('updateStore', $user_permission) || in_array('viewStore', $user_permission) || in_array('deleteStore', $user_permission)): ?>
            <li id="storeNav">
              <a href="<?php echo base_url('Controller_Warehouse/') ?>">
                <i class="fa fa-institution"></i> <span>Warehouse</span>
              </a>
            </li>
          <?php endif; ?>-->

      <!-- <?php if (in_array('createAttribute', $user_permission) || in_array('updateAttribute', $user_permission) || in_array('viewAttribute', $user_permission) || in_array('deleteAttribute', $user_permission)): ?>
        <li id="attributeNav">
          <a href="<?php echo base_url('Controller_Element/') ?>">
            <i class="fa fa-files-o"></i> <span>Elements</span>
          </a>
        </li>
      <?php endif; ?> -->
      <?php if (in_array('createCustomers', $user_permission) || in_array('updateCustomers', $user_permission) || in_array('viewCustomers', $user_permission) || in_array('deleteCustomers', $user_permission)): ?>
        <li id="customerNav">
          <a href="<?php echo base_url('Controller_Customer/') ?>">
            <i class="fa fa-users"></i> <span>Users</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if (in_array('createMedicines', $user_permission) || in_array('updateMedicines', $user_permission) || in_array('viewMedicines', $user_permission) || in_array('deleteMedicines', $user_permission)): ?>
        <li id="medicineNav">
          <a href="<?php echo base_url('Controller_Medicines/') ?>">
            <i class="fa fa-medkit"></i> <span>Medicines</span>
          </a>
        </li>
      <?php endif; ?>


      <?php if (in_array('createProduct', $user_permission) || in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
        <li class="treeview" id="mainProductNav">
          <a href="#">
            <i class="fa fa-cube"></i>
            <span>Inward Medicines</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if (in_array('createProduct', $user_permission)): ?>
              <li id="addProductNav"><a href="<?php echo base_url('Controller_Products/create') ?>"><i class="fa fa-circle-o"></i>Inward Medicines</a></li>
            <?php endif; ?>
            <?php if (in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
              <li id="manageProductNav"><a href="<?php echo base_url('Controller_Products') ?>"><i class="fa fa-circle-o"></i> Manage Inward Medicines</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>


      <?php if (in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
        <li class="treeview" id="mainOrdersNav">
          <a href="#">
            <i class="fa fa-medkit"></i>
            <span>Manage Transaction</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <!-- <ul class="treeview-menu">
            <?php if (in_array('createOrder', $user_permission)): ?>
              <li id="addOrderNav"><a href="<?php echo base_url('Controller_Orders/create') ?>"><i class="fa fa-circle-o"></i> Outward Medicines</a></li>
            <?php endif; ?>
            <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
              <li id="manageOrdersNav"><a href="<?php echo base_url('Controller_Orders') ?>"><i class="fa fa-circle-o"></i> Manage Outward Medicines</a></li>
            <?php endif; ?>
          </ul> -->
          <ul class="treeview-menu">

            <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
              <li id="manageOrdersNav"><a href="<?php echo base_url('MedicineController/add_transaction_form') ?>"><i class="fa fa-circle-o"></i> Create New</a></li>
              <li id="manageOrdersNav"><a href="<?php echo base_url('MedicineController/customer_medicine_view') ?>"><i class="fa fa-circle-o"></i> Edit</a></li>
            <?php endif; ?>
            <?php if (in_array('createOrder', $user_permission)): ?>
              <li id="addOrderNav"><a href="<?php echo base_url('MedicineController/view_transactions') ?>"><i class="fa fa-circle-o"></i>History</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>

      <!-- <?php if (in_array('createUsed', $user_permission) || in_array('updateUsed', $user_permission) || in_array('viewUsed', $user_permission) || in_array('deleteUsed', $user_permission)): ?>
        <li class="treeview" id="mainUsedNav">
          <a href="#">
            <i class="fa fa-recycle"></i>
            <span>Used Medicines</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if (in_array('createUsed', $user_permission)): ?>
              <li id="addUsedNav"><a href="<?php echo base_url('Controller_Used/create') ?>"><i class="fa fa-circle-o"></i>Used Medicines</a></li>
            <?php endif; ?>
            <?php if (in_array('updateUsed', $user_permission) || in_array('viewUsed', $user_permission) || in_array('deleteUsed', $user_permission)): ?>
              <li id="manageUsedNav"><a href="<?php echo base_url('Controller_Used') ?>"><i class="fa fa-circle-o"></i> Manage Used Medicines</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?> -->
      <!-- <?php if (in_array('updateStock', $user_permission) || in_array('viewStock', $user_permission) || in_array('deleteStock', $user_permission)): ?>
        <li id="manageStockNav"><a href="<?php echo base_url('Controller_Stock') ?>"><i class="fa fa-cubes"></i> Manage Stock</a></li>
      <?php endif; ?>

      <?php if (in_array('updateHistory', $user_permission) || in_array('viewHistory', $user_permission) || in_array('deleteHistory', $user_permission)): ?>
        <li id="manageHistoryNav"><a href="<?php echo base_url('Controller_History') ?>"><i class="fa fa-history"></i> Manage History</a></li>
      <?php endif; ?> -->


      <!-- <?php if ($user_permission): ?>
          <?php if (in_array('createUser', $user_permission) || in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
            <li class="treeview" id="mainUserNav">
            <a href="#">
              <i class="fa fa-users"></i>
              <span>Members</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if (in_array('createUser', $user_permission)): ?>
              <li id="createUserNav"><a href="<?php echo base_url('Controller_Members/create') ?>"><i class="fa fa-circle-o"></i> Add Members</a></li>
              <?php endif; ?>

              <?php if (in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
              <li id="manageUserNav"><a href="<?php echo base_url('Controller_Members') ?>"><i class="fa fa-circle-o"></i> Manage Members</a></li>
            <?php endif; ?>
            </ul>
          </li>
          <?php endif; ?> -->

      <!-- <?php if (in_array('createGroup', $user_permission) || in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
            <li class="treeview" id="mainGroupNav">
              <a href="#">
                <i class="fa fa-recycle"></i>
                <span>Permission</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if (in_array('createGroup', $user_permission)): ?>
                  <li id="addGroupNav"><a href="<?php echo base_url('Controller_Permission/create') ?>"><i class="fa fa-circle-o"></i> Add Permission</a></li>
                <?php endif; ?>
                <?php if (in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
                <li id="manageGroupNav"><a href="<?php echo base_url('Controller_Permission') ?>"><i class="fa fa-circle-o"></i> Manage Permission</a></li>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?> -->

      <!--  <?php if (in_array('viewReports', $user_permission)): ?>
            <li id="reportNav">
              <a href="<?php echo base_url('reports/') ?>">
                <i class="glyphicon glyphicon-stats"></i> <span>Reports</span>
              </a>
            </li>
          <?php endif; ?> -->


      <!-- <?php if (in_array('updateCompany', $user_permission)): ?>
            <li id="companyNav"><a href="<?php echo base_url('Controller_Company/') ?>"><i class="fa fa-bank"></i> <span>Company</span></a></li>
          <?php endif; ?> -->

    <?php endif; ?>
    <!-- user permission info -->
    <li><a href="#" onclick="confirmLogout()"><i class="glyphicon glyphicon-log-out"></i> <span>Logout</span></a></li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

<script>
  function confirmLogout() {
    Swal.fire({
      title: 'Are you sure?',
      text: "You will be logged out!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, logout!',
      customClass: {
        popup: 'swal-popup',
        confirmButton: 'swal-confirm-button',
        cancelButton: 'swal-cancel-button'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "<?php echo base_url('auth/logout') ?>";
      }
    });
  }
</script>

<style>
  .swal-popup {
    font-size: 1em;
    /* Medium font size for the popup */
  }

  .swal-confirm-button,
  .swal-cancel-button {
    padding: 10px 20px;
    /* Medium button size */
    font-size: 1em;
    /* Medium font size for buttons */
  }
</style>

<!-- Include SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>