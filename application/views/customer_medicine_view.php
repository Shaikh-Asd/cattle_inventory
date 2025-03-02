<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Manage Medicines
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Medicines</li>
        </ol>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="form-group col-md-3">
                        <label for="customerSelect">Vendor Name</label>
                        <select id="customerSelect" class="form-control select2" onchange="fetchCustomerMedicine()">
                            <option value="">Select a user</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="table-responsive">
                        <h3>Medicine Summary</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Medicine Name</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="medicineSummary">
                                <!-- Data will be filled via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal for Medicine Breakdown -->
<div id="medicineModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="medicineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicineModalLabel">Medicine Breakdown</h5>
                <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Given</th>

                        </tr>
                    </thead>
                    <tbody id="medicineBreakdown">
                        <!-- Data will be filled via AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateStock()">Update Stock</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".select2").select2({
            allowClear: true
        });
        fetchMedicines(); // Fetch medicines on page load
    });

    function fetchMedicines() {
        $.get("<?= base_url('MedicineController/get_all_medicines') ?>", function(data) {
            let medicines = JSON.parse(data);
            let rows = "";
            medicines.forEach((med, index) => {
                rows += `<tr>
                    <td>${index + 1}</td>
                    <td>${med.name}</td>
                    <td>${med.dead_stock}</td>
                    <td><span class="label label-success">${med.status}</span></td>
                    <td>
                        <button onclick="editMedicine(${med.id})" class="btn btn-warning">Edit</button>
                        <button onclick="deleteMedicine(${med.id})" class="btn btn-danger">Delete</button>
                    </td>
                </tr>`;
            });
            $("#medicineSummary").html(rows);
        });
    }



    function editMedicine(medicineId) {
        // Logic to edit medicine
    }

    function deleteMedicine(medicineId) {
        // Logic to delete medicine
    }

    function fetchCustomerMedicine() {
        let customerId = $("#customerSelect").val();
        console.log("Selected customer id:", customerId);
        if (customerId) {
            $.get("<?= base_url('MedicineController/get_customer_medicines/') ?>" + customerId, function(data) {
                let medicines = JSON.parse(data);
                let rows = "";
                medicines.forEach(med => {
                    rows += `<tr>
                    <td>${med.id}</td>
                    <td>${med.name}</td>
                    <td>
                        <button class="btn btn-danger" onclick="adjustStock(${med.id}, 'subtract')">-</button>
                        <span id="stock_${med.id}">${med.total_given}</span>
                        <button class="btn btn-success" onclick="adjustStock(${med.id}, 'add')">+</button>
                        <button class="btn btn-primary" onclick="updateStock(${med.id}, ${med.total_given > 0 ? 1 : 2})">Update</button>
                    </td>
                    <td><button class="btn btn-primary" onclick="viewBreakdown(${customerId}, ${med.id})">View Details</button></td>
                </tr>`;
                });
                $("#medicineSummary").html(rows);
            });
        } else {
            $("#medicineSummary").html("");
        }
    }

    function adjustStock(medicineId, operation) {
        let stockElement = document.getElementById("stock_" + medicineId);
        let currentValue = parseInt(stockElement.innerText);

        if (operation === "add") {
            stockElement.innerText = currentValue + 1; // Increase stock
        } else if (operation === "subtract" && currentValue > 0) {
            stockElement.innerText = currentValue - 1; // Decrease stock (min 0)
        }
    }

    function updateStock(medicineId, type) {
        let stockElement = document.getElementById("stock_" + medicineId);
        let updatedStock = parseInt(stockElement.innerText);

        // Prepare the data in the expected format
        let updatedData = [{
            detail_id: medicineId,
            quantity_given: updatedStock,
            type: type // Include the type in the data
        }];

        $.post("<?= base_url('MedicineController/update_stock') ?>", {
            detail_id: medicineId,
            quantity_given: updatedStock,
            type: type
        }, function(response) {
            alert("Stock updated successfully!");
            fetchCustomerMedicine(); // Refresh the medicine summary
        }, "json");
    }

    function viewBreakdown(customerId, medicineId) {
        $.get("<?= base_url('MedicineController/get_medicine_breakdown/') ?>" + customerId + "/" + medicineId, function(data) {
            let medicines = JSON.parse(data);
            let rows = "";
            medicines.forEach(med => {
                rows += `<tr>
                <td>${med.name}</td>
                <td>${med.quantity_given}</td>
                </tr>`;
            });
            $("#medicineBreakdown").html(rows);
            $("#medicineModal").modal('show');
        });
    }

    function closeModal() {
        $("#medicineModal").modal('hide');
    }
</script>