<?php
include('../includes/header.php');
include('../includes/nav.php');
include('../../config/dbcon.php');
include ('../../auth/auth_check_admin.php');
?>

<!-- Create Modal -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="customerForm" action="addCustomer.php" method="POST">
        <input type="hidden" name="action" value="create">
        <div class="modal-header">
          <h5 class="modal-title" id="editcustomerModalLabel">Create New Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="givenCustomerName" name="name" placeholder="" required>
            <label for="givenCustomerName">Customer Name</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="givenCustomerContact" name="contact" placeholder="" required>
            <label for="givenCustomerContact">Customer Contact</label>
          </div>
          <div class="form-floating mb-3">
            <textarea type="text" class="form-control" id="givenCustomerAddress" name="address" placeholder="" required></textarea>
            <label for="givenCustomerAddress">Customer Address</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="createCustomerBtn">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editcustomerModal" tabindex="-1" aria-labelledby="editcustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="editFormModal" action="addCustomer.php" method="POST">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="customerId" id="customerIdToEdit">
        <div class="modal-header">
          <h5 class="modal-title" id="editcustomerModalLabel">Edit <strong id="edit-modal-customer-name"></strong></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="customerNameToEdit" name="customerName" placeholder="" required>
            <label for="customerName" class="form-label">Customer Name</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="customerContactToEdit" name="customerContact" placeholder="" required>
            <label for="customerContact" class="form-label">Customer Contact</label>
          </div>
          <div class="form-floating mb-3">
            <textarea type="text" class="form-control" id="customerAddressToEdit" name="customerAddress" placeholder="" required></textarea>
            <label for="customerAddress" class="form-label">Customer Address</label>
          </div>
        </div>
        <div class=" modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="deleteFormModal" action="addCustomer.php" method="POST">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="customerId" id="customerIdToDelete">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteCustomerModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete <strong id="modal-customer-name"></strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--table container-->

<div class="w-100 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
  <div class="d-flex justify-content-around mb-4 fw-bold">
    <h3>Saved Customers</h3>
    <button class="btn btn-primary create-button"
      data-bs-toggle="modal"
      data-bs-target="#createCustomerModal">
      Create New
    </button>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Sl.no</th>
        <th scope="col">Name</th>
        <th scope="col">Contact</th>
        <th scope="col">Address</th>
        <th scope="col">Manage</th>
      </tr>
    </thead>
    <tbody>
      <?php
      try {
        $stmt = $pdo->prepare("SELECT * FROM customers ORDER BY customerName ASC");
        $stmt->execute();
        $customer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($customer)) {
          $slNo = 1;
          foreach ($customer as $customer) {
      ?>
            <tr>
              <th scope="row"><?= $slNo++; ?></th>
              <td><?= htmlspecialchars($customer['customerName']); ?></td>
              <td><?= htmlspecialchars($customer['customerContact']); ?></td>
              <td><?= htmlspecialchars($customer['customerAddress']); ?></td>
              <td>
                <a href="#"
                  class="edit-button"
                  data-bs-toggle="modal"
                  data-bs-target="#editcustomerModal"
                  data-customer-id="<?= htmlspecialchars($customer['customerId']); ?>"
                  data-customer-name="<?= htmlspecialchars($customer['customerName']); ?>"
                  data-customer-contact="<?= htmlspecialchars($customer['customerContact']); ?>"
                  data-customer-address="<?= htmlspecialchars($customer['customerAddress']); ?>">
                  <i class="bi bi-pen px-2"></i>
                </a>
                <a href="#"
                  class="delete-button"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteCustomerModal"
                  data-customer-id="<?= htmlspecialchars($customer['customerId']); ?>"
                  data-customer-name="<?= htmlspecialchars($customer['customerName']); ?>">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          <?php
          }
        } else {
          ?>
          <tr>
            <td colspan="4" class="text-center">No customer found.</td>
          </tr>
        <?php
        }
      } catch (PDOException $e) {
        ?>
        <tr>
          <td colspan="4" class="text-center text-danger">Error: <?= htmlspecialchars($e->getMessage()); ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
</div>

<script>
  // create script
  document.getElementById('customerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch('addCustomer.php', {
        method: 'POST',
        body: formData,
      })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === 'success') {
          alert('Customer added successfully!');
          form.reset();
          window.location.reload();
        } else {
          alert('Error adding customer: ' + data);
        }
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('A network error occurred.');
      });
  });

  // delete script
  document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        const customerName = button.getAttribute('data-customer-name');
        const customerId = button.getAttribute('data-customer-id');
        document.getElementById('customerIdToDelete').value = customerId;
        document.getElementById('modal-customer-name').textContent = customerName;
      });
    });
  });

  document.getElementById("deleteFormModal").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch("addCustomer.php", {
        method: "POST",
        body: formData,
      })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === 'success') {
          alert('Customer deleted successfully!');
          window.location.reload();
        } else {
          alert('Error deleting customer: ' + data);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred while processing the request.");
      });
  });

  // edit script
  document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-button');
    const saveButton = document.querySelector('#editFormModal button[type="submit"]');
    const formInputs = document.querySelectorAll(
      '#editFormModal input, #editFormModal textarea'
    );
    let originalValues = {};

    function checkForChanges() {
      let hasChanged = false;
      formInputs.forEach((input) => {
        if (input.value !== originalValues[input.name]) {
          hasChanged = true;
        }
      });
      saveButton.disabled = !hasChanged;
    }

    editButtons.forEach((button) => {
      button.addEventListener('click', function() {
        const customerId = button.getAttribute('data-customer-id');
        const customerName = button.getAttribute('data-customer-name');
        const customerContact = button.getAttribute('data-customer-contact');
        const customerAddress = button.getAttribute('data-customer-address');

        document.getElementById('customerIdToEdit').value = customerId;
        document.getElementById('edit-modal-customer-name').textContent = customerName;
        document.getElementById('customerNameToEdit').value = customerName;
        document.getElementById('customerContactToEdit').value = customerContact;
        document.getElementById('customerAddressToEdit').value = customerAddress;

        originalValues = {
          customerName: customerName,
          customerContact: customerContact,
          customerAddress: customerAddress,
        };

        saveButton.disabled = true;
      });
    });

    formInputs.forEach((input) => {
      input.addEventListener('input', checkForChanges);
    });

    document.getElementById('editFormModal').addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(this);
      fetch('addCustomer.php', {
          method: 'POST',
          body: formData,
        })
        .then((response) => response.text())
        .then((data) => {
          if (data.trim() === 'success') {
            alert('Customer updated successfully!');
            window.location.reload();
          } else {
            alert('Error updating customer: ' + data);
          }
        })
        .catch((error) => console.error('Error:', error));
    });
  });
</script>

<?php include('../includes/footer.php') ?>