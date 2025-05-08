<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Medicine Stock</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Medicine Stock</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Current Medicine Stock</h3>
                        </div>
                        <div class="card-body">
                            <table id="medicineStockTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Medicine Name</th>
                                        <th>Current Stock</th>
                                        <th>Min Stock</th>
                                        <th>Max Stock</th>
                                        <th>Unit</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($medicines) { ?>
                                        <?php foreach($medicines as $medicine) { ?>
                                            <tr>
                                                <td><?php echo $medicine->name; ?></td>
                                                <td><?php echo $medicine->current_stock; ?></td>
                                                <td><?php echo $medicine->min_stock; ?></td>
                                                <td><?php echo $medicine->max_stock; ?></td>
                                                <td><?php echo $medicine->unit; ?></td>
                                                <td>
                                                    <?php if($medicine->current_stock <= $medicine->min_stock) { ?>
                                                        <span class="badge badge-danger">Low Stock</span>
                                                    <?php } else if($medicine->current_stock >= $medicine->max_stock) { ?>
                                                        <span class="badge badge-success">Full Stock</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-warning">Normal</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="6">No medicines found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#medicineStockTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[0, "asc"]]
    });
});
</script> 