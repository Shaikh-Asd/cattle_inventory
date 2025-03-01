<div class="content-wrapper">
    <section class="content-header">
        <h1>
        Transaction

        </h1>
        <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Select Customer</li>
        </ol>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-lg-12">
                <div class="Box">
                    <h2>Select Customer</h2>
                    <select id="customerSelect" onchange="fetchCustomerMedicine()">
                        <option value="">Select Customer</option>
                        <?php 
                        foreach ($customers as $customer): ?>
                            <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-group">
                        <label for="customers">Vendor Name</label>
                        <select class="form-control select2" id="customerSelect" onchange="fetchCustomerMedicine()">
                        <option value="">Select a user</option>
                        <?php foreach ($customers as $customer): ?>
        
                            <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
                        <?php endforeach ?>
                        </select>
                    </div>
                    <div class="table-responsive">
                        <h3>Medicine Summary</h3>
                        <table border="1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Total Given</th>
                                    <th>Actions</th>
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
 <div id="medicineModal" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, 0); background: white; padding: 20px; border: 1px solid black;">
        <h3>Medicine Breakdown</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Given</th>
                    <th>Used</th>
                    <th>Returned</th>
                    <th>Balance</th>
                    <th>Adjust</th>
                </tr>
            </thead>
            <tbody id="medicineBreakdown">
                <!-- Data will be filled via AJAX -->
            </tbody>
        </table>
        <button onclick="updateStock()">Update Stock</button>
        <button onclick="closeModal()">Close</button>
    </div>
<script>

$(document).ready(function() {
    // $(".select2").select2();
    $(".select2").select2({
        // placeholder: "Select a medicine",
        allowClear: true
      });
});
function fetchCustomerMedicine() {
    let customerId = $("#customerSelect").val();
    console.log("customer id",customerId);
    if (customerId) {
        $.get("<?= base_url('MedicineController/get_customer_medicines/') ?>" + customerId, function(data) {
            let medicines = JSON.parse(data);
            let rows = "";
            medicines.forEach(med => {
                rows += `<tr>
                    <td>${med.name}</td>
                    <td>${med.total_given}</td>
                    <td><button onclick="viewBreakdown(${customerId}, ${med.id})">View Details</button></td>
                </tr>`;
            });
            $("#medicineSummary").html(rows);
        });
    } else {
        $("#medicineSummary").html("");
    }
}

function viewBreakdown(customerId, medicineId) {
    $.get("<?= base_url('MedicineController/get_medicine_breakdown/') ?>" + customerId + "/" + medicineId, function(data) {
        let medicines = JSON.parse(data);
        let rows = "";
        medicines.forEach(med => {
            rows += `<tr>
                <td>${med.name}</td>
                <td>
                    <button onclick="adjustGivenQuantity(${med.transaction_detail_id}, 'subtract')">-</button>
                    <input type="number" id="given_qty_${med.transaction_detail_id}" value="${med.quantity_given}" min="1" readonly>
                    <button onclick="adjustGivenQuantity(${med.transaction_detail_id}, 'add')">+</button>
                </td>
            </tr>`;
        });
        $("#medicineBreakdown").html(rows);
        $("#medicineModal").show();
    });
}

function adjustGivenQuantity(detailId, operation) {
    let inputField = document.getElementById("given_qty_" + detailId);
    let currentValue = parseInt(inputField.value);

    if (operation === "add") {
        inputField.value = currentValue + 1; // Increase given quantity
    } else if (operation === "subtract" && currentValue > 1) {
        inputField.value = currentValue - 1; // Decrease given quantity (min 1)
    }
}

function updateStock() {
    let updatedData = [];

    // Collect updated given quantity data
    $("input[id^='given_qty_']").each(function () {
        let detailId = $(this).attr("id").split("_")[2]; // Extract ID from input field
        let newGivenQuantity = $(this).val();
        updatedData.push({ detail_id: detailId, quantity_given: newGivenQuantity });
    });

    $.post("<?= base_url('MedicineController/update_stock') ?>", { updated_data: updatedData }, function(response) {
        alert("Stock updated successfully!");
        fetchCustomerMedicine();
        $("#medicineModal").hide();
    }, "json");
}


function closeModal() {
    $("#medicineModal").hide();
}
</script>