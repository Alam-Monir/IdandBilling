<?php
include('includes/header.php');
include('includes/nav.php');
include('../config/dbcon.php');
?>

<style>
    .quantity i {
        visibility: hidden;
        opacity: 0;
        transition: visibility 0s, opacity 0.3s;
    }

    .quantity:hover i {
        visibility: visible;
        opacity: 1;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        width: 300px;
    }

    #quantityInput {
        width: 100%;
        padding: 5px;
        margin: 10px 0;
        text-align: center;
    }

    #suggestions {
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        background: #fff;
    }

    .list-group-item {
        cursor: pointer;
    }

    .list-group-item:hover {
        background: #f0f0f0;
    }
</style>


<!--Make an Invoice-->

<div class="card position-absolute start-0 bg-body-tertiary h-100 " style="width:35%;">

    <div class="card d-flex align-items-center h-100">
        <h4 class="pt-4">Make An Invoice</h4>
        <div class="form-floating w-50 mt-4">
            <input type="date" id="invoiceDate" class="form-control mt-3" placeholder="Invoice Date" required>
            <label for="invoiceDate">Invoice Date</label>
        </div>
        <div class="form-floating w-50">
            <input type="text" id="customerName" class="form-control mt-3" placeholder="Customer Name" required>
            <label for="customerName">Customer Name</label>
            <ul id="suggestions" class="list-group position-absolute w-50"></ul>
        </div>
        <div class="form-floating w-50">
            <input type="text" id="customerAddress" class="form-control mt-3" placeholder="Customer Address" required>
            <label for="customerAddress">Customer Address</label>
        </div>
        <div class="form-floating w-50">
            <input type="text" id="customerContact" class="form-control mt-3" placeholder="Customer Contact" pattern="\d{10}" title="Please enter 10 digits" required>
            <label for="customerContact">Customer Contact</label>
        </div>

        <div class="form-floating w-50">
            <input type="text" id="Items" class="form-control mt-3" placeholder="Items" title="Please enter the items" required>
            <label for="Items">Items</label>
        </div>

        <div class="input-group mb-2 w-50 mt-3">
            <select class="form-select" id="gstSelector">
                <option disabled selected> Select GST</option>
                <option value="1">No GST</option>
                <option value="2">5%</option>
                <option value="3">12%</option>
                <option value="4">18%</option>
                <option value="5">33%</option>
            </select>
        </div>

        <div class="form-floating w-50">
            <input type="date" id="deliveryDate" class="form-control mt-3" placeholder="Delivery Date" required>
            <label for="deliveryDate">Delivery Date</label>
        </div>

        <div class="d-grid gap-3 d-md-block mt-3 mb-4">
            <button type="button" class="btn btn-outline-danger">Cancel</button>
            <button type="button" id="export" class="btn btn-outline-success">Export</button>
        </div>
    </div>
</div>

<!--table invoice-->

<div id="invoice" class="ml-5 bg-body-tertiary position-absolute end-0 h-100" style="width: 65%; margin-right:5px">
    <div class="container text-center card h-100">
        <div class="row ">
            <?php
            $query = "SELECT sellerName, address, email, contact, gstNo FROM sellerInfo LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $seller = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>

            <div id="sellerInfo" class="col-sm-5 col-md-6 fw-bold mt-3">
                <?php if ($seller): ?>
                    <?= htmlspecialchars($seller['sellerName']); ?>
                    <div class="fw-normal mt-3 mb-3">
                        Address: <?= htmlspecialchars($seller['address']); ?><br>
                        Email: <?= htmlspecialchars($seller['email']); ?><br>
                        Contact: <?= htmlspecialchars($seller['contact']); ?><br>
                        <span id="sellerGstInfo">GST No. <?= htmlspecialchars($seller['gstNo']); ?></span>
                    </div>
                <?php else: ?>
                    Seller information not available.
                <?php endif; ?>
            </div>
            <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 card position-relative mt-3 mr-3">
                <div class="card w-50 position-absolute top-0 start-0 fw-bold">Invoice Number<br>36734337</div>
                <div class="card w-50 position-absolute top-0 end-0 fw-bold">Date<br><span id="currentDate"></span></div>

                <div id="customerDetails" class="fw-normal mt-5 mb-10">
                    To: <span id="displayCustomerName">Land Debbarma</span> <br>
                    Address: <span id="displayCustomerAddress" class="card-text">near don bosco agartala mark para</span><br>
                    Contact: <span id="displayCustomerContact">123456789</span><br>
                </div>
            </div>
        </div>

        <hr>

        <table class="table" id="dataTable">
            <thead>
                <tr>
                    <th scope="col">SL.no</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Rate</th>
                    <th scope="col" class="gst-column-head">GST</th>
                    <th scope="col" class="gstAmount-column-head">GST Amount</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- To be populated using js -->
            </tbody>
        </table>

        <div class="card mb-5">
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 start-0">Total Amount :</div>
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 end-0" id="totalAmount">₹ 00</div>
        </div>

        <div class="card">
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 start-0 " id="amountInWords">Amount in words : </div>
        </div>
        <div class="footer text-center" style="margin-top: auto; padding: 10px 0;">
            <hr style="margin: 0;">
            <div class="pb-2">Thank You For Doing Business With Us.</div>
        </div>
    </div>
</div>

<!-- Main invoice creation script -->
<script>
    const gstSelector = document.getElementById('gstSelector');
    const gstColumnHead = document.querySelector('.gst-column-head');
    const gstAmountColumnHead = document.querySelector('.gstAmount-column-head');
    const sellerGstInfo = document.getElementById('sellerGstInfo');

    const itemsInput = document.getElementById('Items');
    const dataTableBody = document.querySelector('#dataTable tbody');

    // Handle GST selection and update table
    gstSelector.addEventListener('change', () => {
        const selectedGST = gstSelector.value;

        if (selectedGST === "1" || selectedGST === "") {
            // Hide GST and GST Amount columns
            document.querySelectorAll('.gst-column').forEach(gstCell => gstCell.style.display = 'none');
            document.querySelectorAll('.gstAmount-column').forEach(gstAmountCell => gstAmountCell.style.display = 'none');
            gstColumnHead.style.display = 'none';
            gstAmountColumnHead.style.display = 'none';
            sellerGstInfo.style.display = 'none';
        } else {
            // Show GST and GST Amount columns
            document.querySelectorAll('.gst-column').forEach(gstCell => gstCell.style.display = 'table-cell');
            document.querySelectorAll('.gstAmount-column').forEach(gstAmountCell => gstAmountCell.style.display = 'table-cell');
            gstColumnHead.style.display = 'table-cell';
            gstAmountColumnHead.style.display = 'table-cell';
            sellerGstInfo.style.display = 'inline';
        }

        // Update GST values in the table for existing rows
        updateGstAmount(selectedGST);
        updateTotalAmount();
    });

    // Function to calculate and update GST Amount and Total Amount in the table
    function updateGstAmount(selectedGST) {
        const rows = document.querySelectorAll('#dataTable tbody tr');

        rows.forEach(row => {
            const rate = parseFloat(row.querySelector('.price-value').textContent);
            const quantity = parseInt(row.querySelector('.quantity-value').textContent, 10);
            const gstAmountCell = row.querySelector('.gstAmount-column');
            const gstCell = row.querySelector('.gst-column');
            const amountCell = row.querySelector('.amount-column');

            if (rate > 0 && quantity > 0) {
                const totalAmount = rate * quantity;
                let gstAmount = 0;

                // Apply selected GST
                if (selectedGST === "2") {
                    gstAmount = totalAmount * 0.05;
                    gstCell.textContent = "5%";
                } else if (selectedGST === "3") {
                    gstAmount = totalAmount * 0.12;
                    gstCell.textContent = "12%";
                } else if (selectedGST === "4") {
                    gstAmount = totalAmount * 0.18;
                    gstCell.textContent = "18%";
                } else if (selectedGST === "5") {
                    gstAmount = totalAmount * 0.33;
                    gstCell.textContent = "33%";
                } else {
                    gstAmount = 0;
                    gstCell.textContent = "";
                }

                // Update cells
                gstAmountCell.textContent = gstAmount.toFixed(2);
                amountCell.textContent = (totalAmount + gstAmount).toFixed(2);
            } else {
                gstCell.textContent = "";
                gstAmountCell.textContent = "";
                amountCell.textContent = "";
            }
        });
    }

    // Populating Table with Items
    itemsInput.addEventListener('input', () => {
        const items = itemsInput.value.split(',').map(item => item.trim()).filter(item => item);

        dataTableBody.innerHTML = '';

        items.forEach((item, index) => {
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
            <td>${index + 1}</td>
            <td>${item}</td>
            <td>
                <div class="quantity">
                    <i class="bi bi-dash decrease-icon" style="cursor: pointer;"></i>
                    <span class="quantity-value">1</span>
                    <i class="bi bi-plus increase-icon" style="cursor: pointer;"></i>
                    <i class="bi bi-pencil edit-quantity-icon py-1" style="cursor: pointer;"></i>
                </div>
            </td>
            <td>
                <div class="quantity">
                    <span class="edit-per">Pcs</span>
                    <i class="bi bi-pencil edit-per-icon py-2" style="cursor: pointer;"></i>
                </div>
            </td>
            <td>
                <div class="quantity">
                    <span class="price-value">100</span>
                    <i class="bi bi-pencil edit-price-icon py-1" style="cursor: pointer;"></i>
                </div>
            </td>
            <td class="gst-column"></td>
            <td class="gstAmount-column"></td>
            <td class="amount-column"></td>
        `;

            // Add event listeners
            const decreaseIcon = newRow.querySelector('.decrease-icon');
            const increaseIcon = newRow.querySelector('.increase-icon');
            const quantityValue = newRow.querySelector('.quantity-value');
            const editQuantityIcon = newRow.querySelector('.edit-quantity-icon');
            const priceValue = newRow.querySelector('.price-value');
            const editPriceIcon = newRow.querySelector('.edit-price-icon');
            const perValue = newRow.querySelector('.edit-per');
            const editPerIcon = newRow.querySelector('.edit-per-icon');

            decreaseIcon.addEventListener('click', () => {
                let quantity = parseInt(quantityValue.textContent, 10);
                if (quantity > 1) {
                    quantity -= 1;
                    quantityValue.textContent = quantity;
                    const selectedGST = gstSelector.value;
                    updateGstAmount(selectedGST);
                    updateTotalAmount();
                }
            });

            increaseIcon.addEventListener('click', () => {
                let quantity = parseInt(quantityValue.textContent, 10);
                quantity += 1;
                quantityValue.textContent = quantity;
                const selectedGST = gstSelector.value;
                updateGstAmount(selectedGST);
                updateTotalAmount();
            });

            editQuantityIcon.addEventListener('click', () => {
                openEditModal(quantityValue, 'Quantity', (newQuantity) => {
                    quantityValue.textContent = newQuantity;
                    const selectedGST = gstSelector.value;
                    updateGstAmount(selectedGST);
                    updateTotalAmount();
                });
            });

            editPriceIcon.addEventListener('click', () => {
                openEditModal(priceValue, 'Price', (newPrice) => {
                    priceValue.textContent = newPrice;
                    const selectedGST = gstSelector.value;
                    updateGstAmount(selectedGST);
                    updateTotalAmount();
                });
            });

            editPerIcon.addEventListener('click', () => {
                openEditModal(perValue, 'Unit (e.g., Pcs)');
            });

            dataTableBody.appendChild(newRow);
        });

        const selectedGST = gstSelector.value;
        updateGstAmount(selectedGST);
        updateTotalAmount();
    });

    // Function to update the total amount and display the amount in words
    function updateTotalAmount() {
        let totalAmount = 0;

        // Loop through each row in the table and sum the Amount column
        const rows = document.querySelectorAll('#dataTable tbody tr');
        rows.forEach(row => {
            const amountCell = row.querySelector('.amount-column');
            if (amountCell) {
                totalAmount += parseFloat(amountCell.textContent) || 0;
            }
        });

        // Update the Total Amount display
        const totalAmountElement = document.getElementById('totalAmount');
        totalAmountElement.textContent = `₹ ${totalAmount.toFixed(2)}`;

        // Update the Amount in Words display
        const amountInWordsElement = document.getElementById('amountInWords');
        amountInWordsElement.textContent = `Amount in words: ${numberToWords(totalAmount)}`;
    }


    // Function to convert a number into words
    function numberToWords(num) {
        const ones = [
            "", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine",
            "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen",
            "Eighteen", "Nineteen"
        ];
        const tens = [
            "", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"
        ];
        const thousands = ["", "Thousand", "Lakh", "Crore"];

        if (num === 0) return "Zero";

        let word = "";
        let i = 0;

        // Loop through the number in chunks of 1000 (for thousands, lakhs, etc.)
        while (num > 0) {
            if (num % 1000 !== 0) {
                word = convertHundreds(num % 1000) + thousands[i] + " " + word;
            }
            num = Math.floor(num / 1000);
            i++;
        }

        return word.trim();
    }

    // Function to convert a number less than 1000 to words
    function convertHundreds(num) {
        const ones = [
            "", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine",
            "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen",
            "Eighteen", "Nineteen"
        ];
        const tens = [
            "", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"
        ];

        let word = "";

        if (num >= 100) {
            word += ones[Math.floor(num / 100)] + " Hundred ";
            num = num % 100;
        }

        if (num >= 20) {
            word += tens[Math.floor(num / 10)] + " ";
            num = num % 10;
        }

        if (num > 0) {
            word += ones[num] + " ";
        }

        return word.trim() + " only";
    }
</script>

<!-- edit modal function pencil button -->
<script>
    function openEditModal(targetSpan, title, callback) {
        let modal = document.getElementById('editModal');
        if (!modal) {
            // Create modal if it doesn't exist
            modal = document.createElement('div');
            modal.id = 'editModal';
            modal.innerHTML = `
            <div class="modal-overlay">
                <div class="modal-content">
                    <h5 id="modalTitle"></h5>
                    <input type="text" id="editInput" value="" />
                    <button id="saveEditBtn" class="btn btn-outline-success">Save</button>
                    <button id="closeModalBtn" class="btn btn-outline-secondary">Close</button>
                </div>
            </div>
        `;
            document.body.appendChild(modal);

            // Close button functionality
            modal.querySelector('#closeModalBtn').addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }

        // Dynamically update the modal title and input field
        modal.querySelector('#modalTitle').textContent = `Edit ${title}`;
        modal.querySelector('#editInput').value = targetSpan.textContent;

        // Update the save button functionality for the current target
        const saveButton = modal.querySelector('#saveEditBtn');
        saveButton.onclick = () => {
            const newValue = modal.querySelector('#editInput').value.trim();
            if (newValue) {
                targetSpan.textContent = newValue;
                modal.style.display = 'none';

                if (callback) callback(newValue);
            } else {
                alert(`${title} cannot be empty.`);
            }
        };

        modal.style.display = 'block';
    }
</script>

<!-- Date function -->
<script>
    function getFormattedTodayDate() {
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        return `${day}/${month}/${year}`;
    }

    const invoiceDateInput = document.getElementById('invoiceDate');
    const currentDateSpan = document.getElementById('currentDate');

    currentDateSpan.textContent = getFormattedTodayDate();

    invoiceDateInput.addEventListener('input', () => {
        const selectedDate = invoiceDateInput.value;
        if (selectedDate) {
            const [year, month, day] = selectedDate.split('-');
            const formattedDate = `${day}/${month}/${year}`;
            currentDateSpan.textContent = formattedDate;
        } else {
            currentDateSpan.textContent = getFormattedTodayDate();
        }
    });
</script>

<!-- Invoice export function -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script>
    const invoiceCard = document.querySelector('#invoice');
    const exportButton = document.querySelector('#export');

    function generateUniqueInvoiceNumber() {
        const timestamp = Date.now().toString(16);
        const randomPart = Math.floor(Math.random() * 0xfffff).toString(16);

        const uniqueInvoiceNumber = (timestamp + randomPart).slice(0, 16).padStart(16, '0');
        return uniqueInvoiceNumber;
    }

    exportButton.addEventListener('click', () => {
        const uniqueInvoiceNumber = generateUniqueInvoiceNumber();

        const invoiceNumberElement = document.querySelector('.card.w-50');
        if (invoiceNumberElement) {
            invoiceNumberElement.innerHTML = `Invoice Number<br>${uniqueInvoiceNumber}`;
        }

        if (typeof html2canvas !== 'function' || typeof jsPDF !== 'function') {
            console.error('Required libraries (html2canvas or jsPDF) are not loaded properly.');
            return;
        }

        const canvasOptions = {
            scale: 4
        };

        html2canvas(invoiceCard, canvasOptions).then((canvas) => {
            const imgData = canvas.toDataURL('image/jpeg', 1.0);

            
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
            });

            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);

            pdf.save(`invoice${uniqueInvoiceNumber}.pdf`);

            location.reload();
        }).catch((error) => {
            console.error('Error while exporting the invoice:', error);
        });
    });
</script>

<!-- Customer Input suggestion function -->
<script>
    const input = document.getElementById('customerName');
    const suggestionsBox = document.getElementById('suggestions');
    const addressInput = document.getElementById('customerAddress');
    const contactInput = document.getElementById('customerContact');
    const customerDetails = document.getElementById('customerDetails');

    function updateCustomerDetails() {
        const name = input.value || 'Land Debbarma';
        const contact = contactInput.value || '123456789';
        const address = addressInput.value || 'near don bosco agartala mark para';

        customerDetails.innerHTML = `
        To: <span id="displayCustomerName">${name}</span> <br>
        Address: <span id="displayCustomerAddress" class="card-text">${address}</span> <br>
        Contact: <span id="displayCustomerContact">${contact}</span> <br>
    `;
    }

    input.addEventListener('input', updateCustomerDetails);
    addressInput.addEventListener('input', updateCustomerDetails);
    contactInput.addEventListener('input', updateCustomerDetails);

    input.addEventListener('input', async () => {
        const query = input.value.trim();
        if (query.length === 0) {
            suggestionsBox.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`fetch_customer_names.php?query=${encodeURIComponent(query)}`);
            const suggestions = await response.json();

            suggestionsBox.innerHTML = '';

            suggestions.forEach(({
                customerName,
                customerAddress,
                customerContact
            }) => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = customerName;

                li.addEventListener('click', () => {
                    input.value = customerName;
                    addressInput.value = customerAddress;
                    contactInput.value = customerContact;

                    updateCustomerDetails();

                    suggestionsBox.innerHTML = '';
                });

                suggestionsBox.appendChild(li);
            });
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    });

    document.addEventListener('click', (event) => {
        if (!input.contains(event.target) && !suggestionsBox.contains(event.target)) {
            suggestionsBox.innerHTML = '';
        }
    });
</script>

<!-- Saving Invoice -->
<script>
    document.getElementById("export").addEventListener("click", async () => {
        // Collect form data
        const invoiceDateInput = document.getElementById("invoiceDate").value;
        const deliveryDateInput = document.getElementById("deliveryDate").value;
        const customerName = document.getElementById("customerName").value;
        const customerAddress = document.getElementById("customerAddress").value;
        const customerContact = document.getElementById("customerContact").value;
        const gstPercentage = document.getElementById("gstSelector").value;

        //If today's date field is empty enter current date
        function getFormattedTodayDatedb() {
            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const year = today.getFullYear();
            return `${year}/${month}/${day}`;
        }
        const formattedToday = getFormattedTodayDatedb();
        const invoiceDate = invoiceDateInput || formattedToday;

        // Validation
        if (!deliveryDateInput) {
            alert("Please provide a delivery date.");
            return;
        }
        if (!customerName || !customerAddress || !customerContact) {
            alert("Please provide complete customer details.");
            return;
        }

        // Generate a unique invoice ID
        const invoiceId = generateUniqueInvoiceNumber();

        // Get all rows of the table
        const rows = document.querySelectorAll("table tbody tr");
        const tableData = [];

        // Loop through each row and extract required data
        rows.forEach((row) => {
            const item = row.querySelector("td:nth-child(2)").textContent.trim();
            const quantity = row.querySelector(".quantity-value").textContent.trim();
            const unit = row.querySelector(".edit-per").textContent.trim();
            const rate = row.querySelector(".price-value").textContent.trim();

            tableData.push({
                itemName: item,
                quantity: parseInt(quantity, 10),
                unit: unit,
                unitPrice: parseFloat(rate),
            });
        });

        // Prepare data for the PHP script
        const invoiceData = {
            invoiceNumber: invoiceId,
            invoiceDate: invoiceDate,
            deliveryDate: deliveryDateInput,
            customerName: customerName,
            customerAddress: customerAddress,
            customerContact: customerContact,
            gstPercentage: gstPercentage,
            table: tableData,
        };

        try {
            const response = await fetch("saveInvoice.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(invoiceData),
            });

            const result = await response.json();

            if (result.success) {
                alert("Invoice and items saved successfully!");
                // location.reload();
            } else {
                alert(`Failed to save data: ${result.error || "Unknown error"}`);
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while saving the invoice.");
        }
    });
</script>

<?php include('includes/footer.php') ?>