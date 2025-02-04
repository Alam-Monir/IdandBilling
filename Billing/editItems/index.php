<?php
include('../includes/header.php');
include('../includes/nav.php');
include('../../config/dbcon.php');
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteItem'])) {
  header('Content-Type: application/json');
  try {
    $itemId = $_POST['deleteItemId'];

    // Prepare and execute the DELETE statement
    $stmt = $pdo->prepare("DELETE FROM items WHERE itemId = :itemId");
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_STR);

    if ($stmt->execute()) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Failed to delete the item.']);
    }
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
  exit;
}
?>



<div class="d-flex flex-row bd-highlight mb-1">

  <!-- Create and Delete Item -->
  <div class="w-50 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
    <form class="rounded-3" id="createForm" action="create.php" method="POST">
      <div class="d-flex justify-content-center fw-bold">Edit Items</div>
      <div class="mb-3 m-lg-1" style="margin: auto 15px auto 15px;">
        <label for="itemName" class="form-label" style="margin: 15px ;">Create New Item</label>
        <input type="text" class="form-control" name="itemName" id="itemName" placeholder="Item Name" required>
      </div>
      <div class="mt-4">
        <input type="number" class="form-control ml-1" name="itemPrice" id="itemPrice" step="0.01" placeholder="Item Price" required>
      </div>
      <div class="mb-3 form-check">
      </div>
      <input type="hidden" name="action" value="createItem">
      <div class="mb-4">
        <button type="submit" id="createButton" class="btn btn-success m-lg-2">Create</button>
      </div>
    </form>
    <form class="rounded-3" id="searchForm" action="create.php" method="POST">
      <label for="itemName" class="form-label" style="margin: 15px;">Search Item</label>
      <input type="text" class="form-control" name="itemName" id="itemName" placeholder="Enter Item Name" required>
      <input type="hidden" name="action" value="searchItem">
      <div class="mt-3">
        <button type="submit" class="btn btn-primary m-lg-2">Search</button>
      </div>
    </form>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="deleteFormModal" action="edit.php" method="POST">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="itemId" id="itemIdToDelete">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteItemModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete <strong id="modal-item-name"></strong>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="editFormModal" action="edit.php" method="POST">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="itemId" id="itemIdToEdit">
          <div class="modal-header">
            <h5 class="modal-title" id="editItemModalLabel">Edit <strong id="edit-modal-item-name"></strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="itemName" class="form-label">Item Name</label>
              <input type="text" class="form-control" id="itemNameToEdit" name="itemName" readonly>
              <!-- item name edit logic needs to be added -->
            </div>
            <div class="mb-3">
              <label for="itemPrice" class="form-label">Item Price</label>
              <input type="number" class="form-control" id="itemPriceToEdit" name="itemPrice" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!--right table container-->

  <div class="w-50 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
    <div class="d-flex justify-content-center mb-5 fw-bold">Items</div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Sl.no</th>
          <th scope="col">Name</th>
          <!-- tabel added quantity -->
          <th scope="col">Price</th>
          <th scope="col">Manage</th>
        </tr>
      </thead>
      <tbody>
        <?php
        try {
          $stmt = $pdo->prepare("SELECT itemId, itemName, itemPrice FROM items ORDER BY itemName ASC");
          $stmt->execute();
          $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (!empty($items)) {
            $slNo = 1;
            foreach ($items as $item) {
        ?>
              <tr>
                <th scope="row"><?= $slNo++; ?></th>
                <td><?= htmlspecialchars($item['itemName']); ?></td>
                <td><?= htmlspecialchars($item['itemPrice']); ?></td>
                <td>
                  <a href="#"
                    class="edit-button"
                    data-bs-toggle="modal"
                    data-bs-target="#editItemModal"
                    data-item-id="<?= htmlspecialchars($item['itemId']); ?>"
                    data-item-name="<?= htmlspecialchars($item['itemName']); ?>"
                    data-item-price="<?= htmlspecialchars($item['itemPrice']); ?>">
                    <i class="bi bi-pen px-2"></i>
                  </a>
                  <a href="#"
                    class="delete-button"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteItemModal"
                    data-item-id="<?= htmlspecialchars($item['itemId']); ?>"
                    data-item-name="<?= htmlspecialchars($item['itemName']); ?>">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php
            }
          } else {
            ?>
            <tr>
              <td colspan="4" class="text-center">No items found.</td>
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
</div>

<script>
  document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch('create.php', {
        method: 'POST',
        body: formData,
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          const searchedItems = data.data;
          const tableBody = document.querySelector('table tbody');

          tableBody.innerHTML = '';

          if (searchedItems.length > 0) {
            let slNo = 1;
            searchedItems.forEach(item => {
              const row = `
              <tr>
                <th scope="row">${slNo++}</th>
                <td>${item.itemName}</td>
                <td>${item.itemPrice}</td>
                <td>
                  <a href="#"
                    class="edit-button"
                    data-bs-toggle="modal"
                    data-bs-target="#editItemModal"
                    data-item-id="${item.itemId}"
                    data-item-name="${item.itemName}"
                    data-item-price="${item.itemPrice}">
                    <i class="bi bi-pen px-2"></i>
                  </a>
                  <a href="#"
                    class="delete-button"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteItemModal"
                    data-item-id="${item.itemId}"
                    data-item-name="${item.itemName}">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            `;
              tableBody.insertAdjacentHTML('beforeend', row);
            });
            alert('Items Found!');
          } else {
            tableBody.innerHTML = `
            <tr>
              <td colspan="4" class="text-center">No items found.</td>
            </tr>
          `;
          }
        } else {
          alert(data.message);
        }
      })
      .catch(error => {
        alert('Error: ' + error);
      });
  });
</script>

<script src="script.js"></script>

<?php include('../includes/footer.php') ?>