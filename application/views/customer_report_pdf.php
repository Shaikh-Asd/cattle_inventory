<!DOCTYPE html>
<html>
<head>
    <title>Customer Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <h2>Customer Transaction Report</h2>
    <p><strong>Customer Name:</strong> <?= $customer->name; ?></p>
    <p><strong>Contact:</strong> <?= $customer->contact; ?></p>
    <p><strong>Report Date:</strong> <?= date('Y-m-d'); ?></p>

    <table>
        <tr>
            <th>Transaction Date</th>
            <th>Medicine</th>
            <th>Quantity Given</th>
            <th>Quantity Used</th>
            <th>Quantity Returned</th>
            <th>Balance Quantity</th>
        </tr>
        <?php 
        $total_given = 0;
        $total_used = 0;
        $total_returned = 0;
        $total_balance = 0;

        if (!empty($transactions)) {
            foreach ($transactions as $transaction): 
                $balance = $transaction->quantity_given - ($transaction->quantity_used + $transaction->quantity_returned);

                $total_given += $transaction->quantity_given;
                $total_used += $transaction->quantity_used;
                $total_returned += $transaction->quantity_returned;
                $total_balance += $balance;
        ?>
            <tr>
                <td><?= date('Y-m-d H:i', strtotime($transaction->transaction_date)); ?></td>
                <td><?= $transaction->medicine_name; ?></td>
                <td><?= $transaction->quantity_given; ?></td>
                <td><?= $transaction->quantity_used; ?></td>
                <td><?= $transaction->quantity_returned; ?></td>
                <td><?= $balance; ?></td>
            </tr>
        <?php endforeach; 
        } else { ?>
            <tr>
                <td colspan="6">No transactions found</td>
            </tr>
        <?php } ?>
    </table>

    <div class="summary">
        <p><strong>Total Given:</strong> <?= $total_given; ?></p>
        <p><strong>Total Used:</strong> <?= $total_used; ?></p>
        <p><strong>Total Returned:</strong> <?= $total_returned; ?></p>
        <p><strong>Total Balance:</strong> <?= $total_balance; ?></p>
    </div>

</body>
</html>
