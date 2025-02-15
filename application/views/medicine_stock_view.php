<!DOCTYPE html>
<html>
<head>
    <title>Medicine Stock</title>
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
        .low-stock {
            background-color: red;
            color: white;
        }
        .expiring-soon {
            background-color: orange;
            color: black;
        }
        .expired {
            background-color: darkred;
            color: white;
        }
    </style>
</head>
<body>

    <h2>Medicine Stock Report</h2>

    <table>
        <tr>
            <th>Medicine Name</th>
            <th>Stock Available</th>
            <!-- <th>Expiry Date</th> -->
            <th>Status</th>
        </tr>
        <?php 
        $today = date('Y-m-d');
        $low_stock_threshold = 5; // Define low stock level

        if (!empty($medicines)) {
            foreach ($medicines as $medicine): 
                $row_class = '';
                $status = 'In Stock';

                // Check expiry status
                // if ($medicine->expiry_date < $today) {
                //     $row_class = 'expired';
                //     $status = 'Expired';
                // } elseif ($medicine->expiry_date >= $today && $medicine->expiry_date <= date('Y-m-d', strtotime('+30 days'))) {
                //     $row_class = 'expiring-soon';
                //     $status = 'Expiring Soon';
                // }

                // Check stock level
                if ($medicine->stock <= $low_stock_threshold) {
                    $row_class = 'low-stock';
                    $status = 'Low Stock';
                }
        ?>
            <tr class="<?= $row_class; ?>">
                <td><?= $medicine->name; ?></td>
                <td><?= $medicine->stock; ?></td>
                <!-- <td><?= date('Y-m-d', strtotime($medicine->expiry_date)); ?></td> -->
                <td><?= $status; ?></td>
            </tr>
        <?php endforeach; 
        } else { ?>
            <tr>
                <td colspan="4">No medicines available</td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
