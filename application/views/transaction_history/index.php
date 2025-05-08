<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Transaction History
            <small>Manage transaction history</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transaction History</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Manage Transaction History</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="transactionTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Transaction Type</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function() {
        // Initialize DataTable
        $('#transactionTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "<?php echo base_url('Transaction_history/fetchTransactionData'); ?>",
            "columns": [
                { "data": "id" },
                { 
                    "data": "transaction_type",
                    "render": function(data, type, row) {
                        var label = '';
                        switch(data) {
                            case 'inward':
                                label = '<span class="label label-success">Inward</span>';
                                break;
                            case 'outward':
                                label = '<span class="label label-danger">Outward</span>';
                                break;
                            case 'adjustment':
                                label = '<span class="label label-warning">Adjustment</span>';
                                break;
                            default:
                                label = '<span class="label label-default">' + data + '</span>';
                        }
                        return label;
                    }
                },
                { "data": "customer_name" },
                { "data": "created_at" },
                {
                    "data": "id",
                    "render": function(data, type, row) {
                        return '<a href="<?php echo base_url("Transaction_history/view/"); ?>' + data + '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>';
                    }
                }
            ],
            "order": [[0, "desc"]],
            "pageLength": 25,
            "language": {
                "emptyTable": "No transactions found",
                "zeroRecords": "No matching transactions found"
            }
        });
    });
</script> 