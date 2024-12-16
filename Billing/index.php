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
</style>


<!--Make an Invoice-->

<div class="card position-absolute start-0 bg-body-tertiary h-100 " style="width: 40%;">

    <div class="card d-flex align-items-center">
        <h4 class="pt-4">Make an Invoice</h4>
        <div class="form-floating w-50">
            <input type="text" id="customerName" class="form-control mt-3" placeholder="Customer Name" required>
            <label for="customerName">Customer Name</label>
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
            <input type="text" id="customerContact" class="form-control mt-3" placeholder="Customer Contact" pattern="\d{10}" title="Please enter 10 digits" required>
            <label for="customerContact">Quantity</label>
        </div>


        <div class="dropdown mb-3 w-50 mt-4">
            <input type="text" class="form-control" id="searchInput" placeholder="Search and select Items..." onfocus="showDropdown()" autocomplete="off">
            <div class="dropdown-menu p-2" id="dropdownMenu" style="width: 100%; max-height: 200px; overflow-y: auto; display: none;">
                <ul class="list-unstyled mb-0" id="dropdownOptions">
                    <?php
                    $query = $pdo->query("SELECT itemName, itemPrice FROM items");

                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $itemName = htmlspecialchars($row['itemName']);
                        $itemPrice = htmlspecialchars($row['itemPrice']);
                        echo "<li>
                                <label>
                                    <input type='checkbox' value='$itemName' data-price='$itemPrice'> $itemName - $itemPrice
                                </label>
                            </li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="input-group mb-3 w-50 mt-3">
            <select class="form-select" id="inputGroupSelect02">
                <option disabled selected> Select GST</option>
                <option value="1">No GST</option>
                <option value="2">18%</option>
                <option value="3">33%</option>
            </select>
        </div>

        <div class="d-grid gap-3 d-md-block mt-3 mb-4">
            <button type="button" class="btn btn-outline-danger">Cancel</button>
            <button type="button" id="export" class="btn btn-outline-success">Export</button>
        </div>
    </div>
</div>

<!--tabel invoice-->

<div id="invoice" class="p-5 ml-5 bg-body-tertiary position-absolute end-0 h-100" style="width: 60%;">
    <div class="container text-center card h-100 card-text">
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
                    <th scope="col">Price</th>
                    <th scope="col" class="gst-column-head">GST</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- To be populated using js -->
            </tbody>
        </table>

        <div class="card mb-5">
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 start-0">Total Amount :</div>
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 end-0" id="totalAmount">₹ 455.00</div>
        </div>

        <div class="card mb-5 pb-lg-5 h-50">
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 start-0" id="amountInWords">Amount in words : Seventy Crore Seventy Lakhs Seventy Seven Thousands Eight Hundreds and Seventy Seven Only</div>
        </div>
        <div class="footer text-center" style="margin-top: auto; padding: 10px 0;">
            <hr style="margin: 0;">
            <div class="pb-2">Thank You For Doing Business With Us.</div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownOptions = document.getElementById('dropdownOptions');

    function showDropdown() {
        dropdownMenu.style.display = 'block';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            dropdownMenu.style.display = 'none';
        }
    });

    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const options = dropdownOptions.querySelectorAll('li');

        options.forEach(option => {
            const text = option.textContent.trim().toLowerCase();
            option.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    dropdownOptions.addEventListener('change', function() {
        const selected = Array.from(dropdownOptions.querySelectorAll('input:checked'))
            .map(checkbox => checkbox.value)
            .join(', ');
        searchInput.value = selected;
    });

    dropdownMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>

<script>
    const today = new Date();
    const formattedDate = today.toLocaleDateString();

    document.getElementById('currentDate').textContent = formattedDate;
</script>

<script>
    const sellerNameInput = document.getElementById('customerName');
    const sellerContactInput = document.getElementById('customerContact');
    const sellerAddressInput = document.getElementById('customerAddress');

    const customerDetails = document.getElementById('customerDetails');

    function updateCustomerDetails() {
        const name = sellerNameInput.value || 'Land Debbarma';
        const contact = sellerContactInput.value || '123456789';
        const address = sellerAddressInput.value || 'near don bosco agartala mark para';

        customerDetails.innerHTML = `
        To: ${name} <br>
        Address: ${address} <br>
        Contact: ${contact} <br>
    `;
    }

    sellerNameInput.addEventListener('input', updateCustomerDetails);
    sellerContactInput.addEventListener('input', updateCustomerDetails);
    sellerAddressInput.addEventListener('input', updateCustomerDetails);
</script>

<script>
    function numberToWords(number) {
        const ones = [
            "", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"
        ];
        const tens = [
            "", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"
        ];
        const teens = [
            "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen",
            "Sixteen", "Seventeen", "Eighteen", "Nineteen"
        ];

        function convertToWords(num) {
            if (num === 0) return "";
            if (num < 10) return ones[num];
            if (num < 20) return teens[num - 10];
            if (num < 100) return `${tens[Math.floor(num / 10)]} ${ones[num % 10]}`.trim();
            if (num < 1000) return `${ones[Math.floor(num / 100)]} Hundred ${convertToWords(num % 100)}`.trim();
            if (num < 100000) return `${convertToWords(Math.floor(num / 1000))} Thousand ${convertToWords(num % 1000)}`.trim();
            if (num < 10000000) return `${convertToWords(Math.floor(num / 100000))} Lakh ${convertToWords(num % 100000)}`.trim();
            return `${convertToWords(Math.floor(num / 10000000))} Crore ${convertToWords(num % 10000000)}`.trim();
        }

        const words = convertToWords(number);
        return words.trim() + (number % 1 === 0 ? " Only" : "");
    }

    function updateGrandTotalAndWords() {
        const rows = document.querySelectorAll('#dataTable tbody tr');
        let grandTotal = 0;

        rows.forEach(row => {
            const quantity = parseInt(row.querySelector('.quantity-value').textContent, 10);
            const price = parseFloat(row.querySelector('td:nth-child(4)').textContent) || 0;
            const gstValue = row.querySelector('.gst-column').textContent;
            let gst = 0;

            if (gstValue.includes('%')) {
                gst = (parseFloat(gstValue) / 100) * (price * quantity);
            }

            const amount = (price * quantity) + gst;
            row.querySelector('.total').textContent = amount.toFixed(2);
            grandTotal += amount;
        });

        const totalAmountDisplay = document.getElementById('totalAmount');
        totalAmountDisplay.textContent = `₹ ${grandTotal.toFixed(2)}`;

        const amountInWordsDisplay = document.getElementById('amountInWords');
        const amountInWords = numberToWords(Math.round(grandTotal));
        amountInWordsDisplay.textContent = `Amount in words : ${amountInWords}`;
    }


    function updateGstInTable(selectedValue) {
        const rows = document.querySelectorAll('#dataTable tbody tr');
        const gstColumns = document.querySelectorAll('.gst-column');
        const gstHeader = document.querySelector('th.gst-column-head');

        if (selectedValue === "1") {
            gstColumns.forEach(col => col.style.display = 'none');
            if (gstHeader) gstHeader.style.display = 'none';
        } else {
            gstColumns.forEach(col => col.style.display = '');
            if (gstHeader) gstHeader.style.display = '';
        }

        const gstValue = selectedValue === "2" ? "18%" : selectedValue === "3" ? "33%" : "";

        rows.forEach(row => {
            const gstColumn = row.querySelector('.gst-column');
            if (gstColumn) {
                gstColumn.textContent = gstValue || '';
            }
        });

        updateGrandTotalAndWords();
    }

    const gstSelect = document.getElementById('inputGroupSelect02');
    gstSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        updateGstInTable(selectedValue);
    });

    document.getElementById('dropdownOptions').addEventListener('change', function(e) {
        if (e.target.type === 'checkbox') {
            updateTable();
        }
    });

    function updateTable() {
        const selectedItems = document.querySelectorAll('#dropdownOptions input[type="checkbox"]:checked');
        const tbody = document.querySelector('#dataTable tbody');
        tbody.innerHTML = '';
        let slNo = 1;
        selectedItems.forEach(item => {
            const itemName = item.value;
            const itemPrice = item.getAttribute('data-price');

            const row = document.createElement('tr');
            row.innerHTML = `
            <th scope="row">${slNo}</th>
            <td>${itemName}</td>
            <td>
                <div class="quantity">
                    <i class="bi bi-dash decrease-icon" style="cursor: pointer;"></i>
                    <span class="quantity-value">1</span>
                    <i class="bi bi-plus increase-icon" style="cursor: pointer;"></i>
                </div>
            </td>
            <td>${itemPrice}</td>
            <td class="gst-column">GST Placeholder</td>
            <td class="total">${itemPrice}</td>
        `;

            tbody.appendChild(row);

            const decreaseIcon = row.querySelector('.decrease-icon');
            const increaseIcon = row.querySelector('.increase-icon');
            const quantitySpan = row.querySelector('.quantity-value');

            increaseIcon.addEventListener('click', () => {
                const currentQuantity = parseInt(quantitySpan.textContent, 10);
                quantitySpan.textContent = currentQuantity + 1;
                updateGrandTotalAndWords();
            });

            decreaseIcon.addEventListener('click', () => {
                const currentQuantity = parseInt(quantitySpan.textContent, 10);
                if (currentQuantity > 1) {
                    quantitySpan.textContent = currentQuantity - 1;
                    updateGrandTotalAndWords();
                }
            });

            slNo++;
        });

        const selectedValue = gstSelect.value;
        updateGstInTable(selectedValue);
    }
</script>

<script>
    const invoiceCard = document.querySelector('#invoice');
    const exportButton = document.querySelector('#export');

    function generateUniqueInvoiceNumber() {
        const timestamp = Date.now();
        const randomPart = Math.floor(1000 + Math.random() * 9000);
        return `${timestamp}${randomPart}`;
    }

    function updateCurrentDate() {
        const currentDateElement = document.querySelector('#currentDate');
        const now = new Date();
        currentDateElement.textContent = now.toLocaleDateString();
    }

    updateCurrentDate();

    exportButton.addEventListener('click', () => {
        const uniqueInvoiceNumber = generateUniqueInvoiceNumber();

        const invoiceNumberElement = document.querySelector('.card.w-50');
        if (invoiceNumberElement) {
            invoiceNumberElement.innerHTML = `Invoice Number<br>${uniqueInvoiceNumber}`;
        }

        if (typeof html2canvas !== 'function') {
            console.error('html2canvas is not loaded properly.');
            return;
        }

        html2canvas(invoiceCard).then((canvas) => {
            const imageUrl = canvas.toDataURL('image/jpeg');
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = 'invoice.jpeg';
            link.click();
        }).catch((error) => {
            console.error('Error while exporting the invoice:', error);
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<?php include('includes/footer.php') ?>