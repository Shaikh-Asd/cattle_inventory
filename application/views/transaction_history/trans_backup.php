<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer Transaction History
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= base_url('Transaction_history') ?>">Transaction History</a></li>
            <li class="active">Customer Transactions</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Customer: <?= $customer_data['name'] ?></h3>
                        <div class="box-tools">
                            <a href="<?= base_url('Controller_Products') ?>" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Transaction Type</th>
                                    <th>Date</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= $transaction['id'] ?></td>
                                        <td>
                                            <?php
                                            switch ($transaction['transaction_type']) {
                                                case 'inward':
                                                    echo '<span class="label label-success">Inward</span>';
                                                    break;
                                                case 'outward':
                                                    echo '<span class="label label-danger">Outward</span>';
                                                    break;
                                                case 'adjustment':
                                                    echo '<span class="label label-warning">Adjustment</span>';
                                                    break;
                                                default:
                                                    echo '<span class="label label-info">' . $transaction['transaction_type'] . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?= date('d-m-Y H:i', strtotime($transaction['created_at'])) ?></td>
                                        <td>
                                            <table class="table table-bordered table-condensed" style="margin-bottom:0;">
                                                <thead>
                                                    <tr>
                                                        <th>Medicine Name</th>
                                                        <th>Quantity</th>
                                                        <th>Operation</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($transaction['details'] as $detail): ?>
                                                        <tr>
                                                            <td><?= $detail['medicine_name'] ?></td>
                                                            <td><?= $detail['quantity'] ?></td>
                                                            <td><?= $detail['operation'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
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