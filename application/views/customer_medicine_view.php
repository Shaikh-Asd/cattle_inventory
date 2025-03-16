
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
                <div class="box box-primary" style="display: flex; flex-direction: column;">
                    <div class="form-group col-md-3">
                        <label for="customerSelect">Manager</label>
                        <select id="customerSelect" class="form-control select2" onchange="fetchCustomerMedicine()">
                            <option value="">Select a user</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="table-responsive" style="padding: 0 15px;">
                        <h3>Medicine Summary</h3>
                        <div class="col-lg-2">
                        <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchTable()" class="form-control mb-2">
                        </div>
                        <div class="col-lg-2">
                        <label>Rows per page:</label>
                        </div>
                        <div class="col-lg-2" style="margin-bottom: 10px ;">
                        <select id="rowsPerPage" onchange="changeRowsPerPage()" class="form-control mb-2" style="width: 100px;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        </div>
                        <table class="table table-bordered" id="medicineSummaryTable" style="margin-top: 10px;">
                            <thead>
                                <tr style="cursor: pointer;">
                                    <th onclick="sortTable(0)">Sr no  ▲</th>
                                    <th onclick="sortTable(1)">Medicine Name  ▲</th>
                                    <th onclick="sortTable(2)">Given  ▲</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="medicineSummaryTableBody">
                                <!-- Data will be filled via AJAX -->
                            </tbody>
                        </table>
                        <div id="pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal for Medicine Breakdown -->
<!-- <div id="medicineModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="medicineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document"> -->
<div class="modal fade" id="medicineModal" tabindex="-1" role="dialog" aria-labelledby="medicineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="medicineModalLabel">Medicine Breakdown</h4>
                <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="col-lg-2">
                        <input type="text" id="medicineBreakdownTableSearchInput" placeholder="Search..." onkeyup="medicineBreakdownTableSearchTable()" class="form-control mb-2">
                        </div>
                        <div class="col-lg-2">
                        <label>Rows per page:</label>
                        </div>
                        <div class="col-lg-2" style="margin-bottom: 10px ;">
                        <select id="rowsPerPage" onchange="medicineBreakdownTableChangeRowsPerPage()" class="form-control mb-2" style="width: 100px;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        </div>
                <table class="table table-bordered table-responsive" id="medicineBreakdownTable">
                    <thead>
                        <tr>
                            <th onclick="sortMedicineBreakdownTable(0)">Medicine ▲</th>
                            <th onclick="sortMedicineBreakdownTable(1)">Given ▲</th>
                            <th onclick="sortMedicineBreakdownTable(2)">Transaction Date ▲</th>

                        </tr>
                    </thead>
                    <tbody id="medicineBreakdownTableBody">
                        <!-- Data will be filled via AJAX -->
                    </tbody>
                </table>
                <div id="medicineBreakdownTablePagination"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


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
            $("#medicineSummaryTableBody").html(rows);
        });
    }

    function formatDateTime(dateString) {
        let date = new Date(dateString);

        // Extracting date parts
        let day = String(date.getDate()).padStart(2, '0'); // Two-digit day
        let month = date.toLocaleString('en-US', {
            month: 'long'
        }); // Full month name
        let year = date.getFullYear();

        // Extracting time parts
        let hours = date.getHours();
        let minutes = String(date.getMinutes()).padStart(2, '0'); // Two-digit minutes
        let ampm = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12 || 12; // Convert 24-hour time to 12-hour format

        return `${day} ${month} ${year} ${hours}:${minutes} ${ampm}`;
    }

    function editMedicine(medicineId) {
        // Logic to edit medicine
    }

    function deleteMedicine(medicineId) {
        // Logic to delete medicine
    }

    function fetchCustomerMedicine() {
        let customerId = $("#customerSelect").val();
        if (customerId) {
            $.get("<?= base_url('MedicineController/get_customer_medicines/') ?>" + customerId, function(data) {
                let medicines = JSON.parse(data);
                let rows = "";
                if (medicines && medicines.length > 0) {

                    medicines.forEach(med => {
                        rows += `<tr>
                        <td>${med.id}</td>
                        <td>${med.name}</td>
                        <td>
                            <button class="btn btn-danger btn-xs" onclick="adjustStock(${med.id}, 'subtract')">-</button>
                            <span id="stock_${med.id}" style="font-size:20px; margin: 10px;">${med.total_given}</span>
                            <button class="btn btn-success btn-xs" onclick="adjustStock(${med.id}, 'add')">+</button>
                            <button class="btn btn-warning btn-xs" onclick="updateStock(${med.transaction_id}, ${med.medicine_id}, ${med.total_given > 0 ? 1 : 2})">Update</button>
                            <td><button class="btn btn-info btn-xs" onclick="viewBreakdown(${customerId}, ${med.id})">View Details</button></td>
                        </td>
                        </tr>`;
                    });
                } else {
                    rows = `<tr><td colspan="4">No medicines Transaction found for this customer.</td></tr>`;
                }
                $("#medicineSummaryTableBody").html(rows);


            });
        } else {
            $("#medicineSummaryTableBody").html("");
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

    function updateStock(transaction_id, medicineId, type) {
        let stockElement = document.getElementById("stock_" + medicineId);
        let updatedStock = parseInt(stockElement.innerText);


        $.post("<?= base_url('MedicineController/update_stock') ?>", {
            transaction_id: transaction_id,
            medicine_id: medicineId,
            quantity_given: updatedStock,
            type: type
        }, function(response) {
            // alert("Stock updated successfully!");
            Swal.fire({
                title: 'Success',
                text: 'Stock updated successfully!',
                icon: 'success'
            });
            fetchCustomerMedicine(); // Refresh the medicine summary
        }, "json");
        fetchCustomerMedicine();
    }

    function viewBreakdown(customerId, medicineId) {
        $.get("<?= base_url('MedicineController/get_medicine_breakdown/') ?>" + customerId + "/" + medicineId, function(data) {
            let medicines = JSON.parse(data);
            let rows = "";
            medicines.forEach(med => {
                rows += `<tr>
                <td>${med.name}</td>
                <td>${med.quantity_given}</td>
                <td>${formatDateTime(med.transaction_date)}</td>
                </tr>`;
            });
            $("#medicineBreakdownTableBody").html(rows);
            $("#medicineModal").modal('show');
 
        });
    }

    function closeModal() {
        $("#medicineModal").modal('hide');
    }
</script>


<script>
 let currentPage = 1;
  let rowsPerPage = 5;

  function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#medicineSummaryTableBody tr");

    rows.forEach(row => {
      let text = row.innerText.toLowerCase();
      row.style.display = text.includes(input) ? "" : "none";
    });
  }

  function sortTable(columnIndex) {
    let table = document.getElementById("medicineSummaryTable");
    let rows = Array.from(table.rows).slice(1);
    let ascending = table.getAttribute("data-sort-order") !== "asc";

    rows.sort((rowA, rowB) => {
      let cellA = rowA.cells[columnIndex].innerText;
      let cellB = rowB.cells[columnIndex].innerText;

      return ascending ? cellA.localeCompare(cellB, undefined, {
          numeric: true
        }) :
        cellB.localeCompare(cellA, undefined, {
          numeric: true
        });
    });

    table.setAttribute("data-sort-order", ascending ? "asc" : "desc");

    document.getElementById("medicineSummaryTableBody").append(...rows);
  }

  function changeRowsPerPage() {
    rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);
    currentPage = 1;
    paginateTable();
  }

  function paginateTable() {
    let rows = document.querySelectorAll("#medicineSummaryTableBody tr");
    let totalRows = rows.length;
    let totalPages = Math.ceil(totalRows / rowsPerPage);

    rows.forEach((row, index) => {
      row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? "" : "none";
    });

    let paginationHTML = "";
    for (let i = 1; i <= totalPages; i++) {
      paginationHTML += `<button onclick="goToPage(${i})" class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-secondary'}">${i}</button> `;
    }
    document.getElementById("pagination").innerHTML = paginationHTML;
  }

  function goToPage(page) {
    currentPage = page;
    paginateTable();
  }
</script>

<!-- medicine Breakdown Table -->
<script>
 let currentPage2 = 1;
  let rowsPerPage2 = 5;

  function medicineBreakdownTableSearchTable() {
    let input = document.getElementById("medicineBreakdownTableSearchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#medicineBreakdownTableBody tr");

    rows.forEach(row => {
      let text = row.innerText.toLowerCase();
      row.style.display = text.includes(input) ? "" : "none";
    });
  }

  function sortMedicineBreakdownTable(columnIndex) {
    let table = document.getElementById("medicineBreakdownTable");
    let rows = Array.from(table.rows).slice(1);
    let ascending = table.getAttribute("data-sort-order") !== "asc";

    rows.sort((rowA, rowB) => {
      let cellA = rowA.cells[columnIndex].innerText;
      let cellB = rowB.cells[columnIndex].innerText;

      return ascending ? cellA.localeCompare(cellB, undefined, {
          numeric: true
        }) :
        cellB.localeCompare(cellA, undefined, {
          numeric: true
        });
    });

    table.setAttribute("data-sort-order", ascending ? "asc" : "desc");

    document.getElementById("medicineBreakdownTableBody").append(...rows);
  }

  function medicineBreakdownTableChangeRowsPerPage() {
    rowsPerPage2 = parseInt(document.getElementById("rowsPerPage").value);
    currentPage2 = 1;
    paginatemedicineBreakdownTable();
  }

  function paginatemedicineBreakdownTable() {
    let rows = document.querySelectorAll("#medicineBreakdownTableBody tr");
    let totalRows = rows.length;
    let totalPages = Math.ceil(totalRows / rowsPerPage2);

    rows.forEach((row, index) => {
      row.style.display = (index >= (currentPage2 - 1) * rowsPerPage2 && index < currentPage2 * rowsPerPage2) ? "" : "none";
    });

    let paginationHTML = "";
    for (let i = 1; i <= totalPages; i++) {
      paginationHTML += `<button onclick="goToPage(${i})" class="btn btn-sm ${i === currentPage2 ? 'btn-primary' : 'btn-secondary'}">${i}</button> `;
    }
    document.getElementById("medicineBreakdownTablePagination").innerHTML = paginationHTML;
  }

  function goToPage(page) {
    currentPage2 = page;
    paginatemedicineBreakdownTable();
  }
</script>