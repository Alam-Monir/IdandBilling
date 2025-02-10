<?php
include("includes/header.php");
include("includes/nav.php");
include("../config/dbcon.php");
include('../auth/auth_check_admin.php');

try {
  // Fetch admin details
  $stmt = $pdo->prepare("SELECT firstName, lastName, contact, email FROM users WHERE isAdmin = 1 LIMIT 1");
  $stmt->execute();
  $admin = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($admin) {
    $firstName = htmlspecialchars($admin['firstName']);
    $lastName = htmlspecialchars($admin['lastName']);
    $phone = htmlspecialchars($admin['contact']);
    $email = htmlspecialchars($admin['email']);
  } else {
    $firstName = $lastName = $phone = $email = "";
  }
} catch (PDOException $e) {
  die("Database error: " . $e->getMessage());
}

// Query to fetch users where isAdmin = 0
// $sql = "SELECT * FROM users WHERE isAdmin = 0";
// $stmt = $pdo->prepare($sql);
// $stmt->execute();

// Fetch all users
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Password Change Modal -->
<div class="modal fade" id="passwordChange" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Change Admin's Password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="passwordChangeForm">
          <div class="form-floating w-80">
            <input type="password" id="oldPassword" name="oldPassword" class="form-control" placeholder="Old Password" autocomplete="off">
            <label for="oldPassword">Old Password</label>
          </div>
          <div class="form-floating w-80">
            <input type="password" id="newPassword" name="newPassword" class="form-control mt-3" placeholder="New Password" autocomplete="off">
            <label for="newPassword">New Password</label>
          </div>
          <div class="form-floating w-80">
            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control mt-3" placeholder="Confirm Password" autocomplete="off">
            <label for="confirmPassword">Confirm Password</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" form="passwordChangeForm">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="deleteFormModal" action="" method="POST">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="userId" id="deleteUserId" value="">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete User ?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-truncate">
          Are you sure you want to delete User <strong id="userToDelete"></strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- left-box -->
<div class="row">
  <div class="col-sm-6 mb-3 mb-sm-0 w-25">
    <div class="card">
      <h5 class="px-3 pt-4 fs-4">Admin Details</h5>
      <div class="card-body">
        <h1 class="fs-6">Change User Name</h1>
        <div class="form-floating w-80">
          <input type="text" id="firstName" class="form-control mt-3" value="<?= $firstName ?>" placeholder="First Name">
          <label for="firstName">First Name</label>
        </div>
        <div class="form-floating w-80 mt-3">
          <input type="text" id="lastName" class="form-control" value="<?= $lastName ?>" placeholder="Last Name">
          <label for="lastName">Last Name</label>
        </div>

        <h1 class="fs-6 mt-4">Change Phone Number</h1>
        <div class="form-floating w-80">
          <input type="number" id="phone" class="form-control mt-3" value="<?= $phone ?>" placeholder="Phone Number">
          <label for="phone">Phone Number</label>
        </div>

        <h1 class="fs-6 mt-3">Change Email Address</h1>
        <div class="form-floating w-80">
          <input type="email" id="email" class="form-control mt-3" value="<?= $email ?>" placeholder="Email">
          <label for="email">Email</label>
        </div>

        <div class="d-grid gap-2 pt-4">
          <button class="btn btn-outline-dark"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#passwordChange">
            Change Password
          </button>
        </div>
        <button type="button" id="saveBtn" class="btn btn-success w-80 mt-3">Save</button>
        <button type="button" class="btn btn-outline-danger w-80 mt-3" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Right Div Box -->

  <div class="col-sm-6 w-75">
    <div class="card">
      <h5 class="fs-4 px-4 pt-4" id="exampleModalLabel">Staff List</h5>
      <div class="card-body vh-100">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">SL.NO</th>
              <th scope="col">Name</th>
              <th scope="col">Contact</th>
              <th scope="col">Email</th>
              <th scope="col">Address</th>
              <th scope="col">Manage</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
              $stmt = $pdo->prepare("SELECT * FROM users WHERE isAdmin = 0");
              $stmt->execute();
              $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
              if (!empty($users)) {
                $slNo = 1;
                foreach ($users as $user) {
                  $usersId = $user['id'];
            ?>
                  <tr>
                    <th scope="row"><?php echo $slNo++; ?></th>
                    <td><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></td>
                    <td><?= htmlspecialchars($user['contact']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= htmlspecialchars($user['address']); ?></td>
                    <td>
                      <a href="#" class="edit-user-button">
                        <i class="bi bi-pen px-2"></i>
                      </a>
                      <a href="#"
                        class="delete-user-button"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteUserModal"
                        data-user-name="<?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?>"
                        data-user-id="<?= htmlspecialchars($user['id']); ?>">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php
                }
              } else {
                ?>
                <tr>
                  <td colspan="8" class="text-center">No Users found.</td>
                </tr>
              <?php
              }
            } catch (PDOException $e) {
              ?>
              <tr>
                <td colspan="8" class="text-center text-danger">Error: <?= htmlspecialchars($e->getMessage()); ?></td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById("saveBtn").addEventListener("click", function() {
    let firstName = document.getElementById("firstName").value.trim();
    let lastName = document.getElementById("lastName").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let email = document.getElementById("email").value.trim();

    let formData = new FormData();
    formData.append("firstName", firstName);
    formData.append("lastName", lastName);
    formData.append("phone", phone);
    formData.append("email", email);

    fetch("update_admin.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        if (data.status === "success") {
          location.reload();
        }
      })
      .catch(error => console.error("Error:", error));
  });
</script>

<script>
  document.getElementById("passwordChangeForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let newPassword = document.getElementById("newPassword").value.trim();
    let confirmPassword = document.getElementById("confirmPassword").value.trim();

    if (newPassword !== confirmPassword) {
      alert("New and Confirm Passwords do not match!");
      return;
    }

    let formData = new FormData(this);

    fetch("change_password.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.text())
      .then(text => {
        try {
          let data = JSON.parse(text);
          alert(data.message);
          if (data.status === "success") {
            document.getElementById("passwordChangeForm").reset();
            let modal = bootstrap.Modal.getInstance(document.getElementById("passwordChange"));
            modal.hide();
          }
        } catch (error) {
          console.error("Invalid JSON Response:", text);
          alert("Unexpected server response. Check console for details.");
        }
      })
      .catch(error => console.error("Fetch Error:", error));
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-user-button');

    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        const usersName = button.getAttribute('data-user-name');
        const usersId = button.getAttribute('data-user-id');
        // console.log(usersId)
        document.getElementById('deleteUserId').value = usersId;
        document.getElementById('userToDelete').textContent = usersName;
      });
    });
  });
</script>

<?php include("includes/footer.php"); ?>