<table border="1">
    <tr>
        <th>Name</th>
        <th>Contact</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?= $customer->name; ?></td>
            <td><?= $customer->contact; ?></td>
            <td>
                <a href="<?= base_url('customer_transactions/'.$customer->id) ?>">View History</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
