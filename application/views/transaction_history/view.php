<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Transaction Details
            <small>View transaction information</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('Transaction_history'); ?>">Transaction History</a></li>
            <li class="active">View Transaction</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Transaction Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px;">Transaction ID</th>
                                        <td><?php echo $transaction_data['id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Transaction Type</th>
                                        <td>
                                            <?php
                                            $type_label = '';
                                            switch($transaction_data['transaction_type']) {
                                                case 'inward':
                                                    $type_label = '<span class="label label-success">Inward</span>';
                                                    break;
                                                case 'outward':
                                                    $type_label = '<span class="label label-danger">Outward</span>';
                                                    break;
                                                case 'adjustment':
                                                    $type_label = '<span class="label label-warning">Adjustment</span>';
                                                    break;
                                                default:
                                                    $type_label = '<span class="label label-default">' . $transaction_data['transaction_type'] . '</span>';
                                            }
                                            echo $type_label;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Customer</th>
                                        <td><?php echo $transaction_data['customer_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td><?php echo date('d M Y H:i', strtotime($transaction_data['created_at'])); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <h4>Transaction Items</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Medicine Name</th>
                                        <th>Quantity</th>
                                        <!-- <th>Unit Price</th> -->
                                        <!-- <th>Total</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($transaction_details as $detail): ?>
                                    <tr>
                                        <td><?php echo $detail['medicine_name']; ?></td>
                                        <td><?php echo $detail['quantity']; ?></td>
                                        <!-- <td>₹<?php echo number_format($detail['unit_price'], 2); ?></td> -->
                                        <!-- <td>₹<?php echo number_format($detail['quantity'] * $detail['unit_price'], 2); ?></td> -->
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <!-- <th colspan="3" class="text-right">Total Amount:</th> -->
                                        <!-- <th>₹<?php echo number_format($transaction['total_amount'], 2); ?></th> -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="<?php echo base_url('Transaction_history'); ?>" class="btn btn-default">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
/.content-wrapper 