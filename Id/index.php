<?php
include("includes/header.php");
include("includes/nav.php");
include("../config/dbcon.php");

function prepareImageUrl($imageUrl)
{
  $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $imageUrl);
  return htmlspecialchars("id/" . $imageUrl);
}
?>
<style>
  .card {
    position: relative;
    width: 400px;
    height: 600px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    background-color: #ffffff;
    transition: all 0.3s ease;
    margin: 20px;
  }

  .card .card-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 10;
  }

  .card:hover .card-overlay {
    opacity: 1;
  }

  .card-overlay button {
    padding: 8px 12px;
    font-size: 0.9rem;
    cursor: pointer;
    margin: 5px;
  }

  .card .card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 8px;
  }

  .card .card-details {
    font-size: 0.8rem;
    font-weight: bold;
    color: #666666;
    line-height: 0.1;
    padding: 10px;
    border-radius: 8px;
  }

  .card-overlay h3 {
    font-size: 1.2rem;
    font-weight: bold;
    color: white;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    background-color: rgba(0, 0, 0, 0.3);
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }
</style>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="deleteForm" action="backend.php" method="POST">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="layoutName" id="deletelayoutName" value="">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-truncate">
          Are you sure you want to delete <strong id="layoutToDelete"></strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="cancelButton"
            data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Id Card Layouts -->
<div class="d-flex justify-content-around">
  <div id="cardContainer" class="d-flex flex-wrap justify-content-center">
    <?php
    try {
      $stmt = $pdo->query("SELECT * FROM idLayout");

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $schoolLogo = prepareImageUrl($row['logo']);
        $principalSign = prepareImageUrl($row['sign']);
        $bgImage = prepareImageUrl($row['bgImage']);

        $id = htmlspecialchars($row['id']);
        $schoolName = htmlspecialchars($row['schoolName']);
        $schoolAddress = htmlspecialchars($row['schoolAdd']);
        $layoutName = htmlspecialchars($row['layoutName']);
        $layoutType = htmlspecialchars($row['layoutType']);

        // Common card styles
        echo <<<HTML
            <div class="card px-auto d-flex flex-column"
                style="width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden; background-color: #ffffff; transition: all 0.3s ease; background-image: url('$bgImage'); background-size: cover; background-position: center; background-repeat: no-repeat; margin: 20px; position: relative;">
                
                <div class="card-content" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 10px; padding: 10px; border-radius: 8px;">
                    <img src="$schoolLogo" alt="School Logo" style="width: 80px; height: 80px;">
                    <h3 class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">$schoolName</h3>
                    <p class="card-text pb-2" style="font-size: 0.8rem; color: #666666; line-height: 0.8;">$schoolAddress</p>
                    <img src="img/profileImage.jpg" alt="Profile Image" style="width: 140px; height: 160px;">
          HTML;

        // Layout-specific content
        if ($layoutType === 'Student') {
          echo <<<HTML
                    <h3 class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 2px;">Student Name</h3>
                    <h3 class="card-title" style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">Class</h3>
                </div>

                    <!-- Layout name, Edit Layout, and Fill Details buttons -->
                <div class="card-overlay">
                    <h3 style="font-size: 1.2rem; font-weight: bold; color: white;">$layoutName</h3>
                    <div>
                        <button class="btn btn-primary edit-button" data-id="$id" style="margin: 5px;">Edit Layout</button>
                        <button class="btn btn-secondary student-fill-button" data-id="$id" style="margin: 5px;">Fill Details</button>
                        <button class="btn btn-danger delete-button" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal" 
                            data-layout-name="$layoutName">
                            Delete
                        </button>
                    </div>
                </div>
                <div class="card-details" style="font-size: 0.8rem; font-weight:bold; color: #666666; line-height: 0.1; padding: 10px; border-radius: 8px;">
                    <p class="card-text pb-1 px-2">Date of Birth: </p>
                    <p class="card-text pb-1 px-2">Blood Group: </p>
                    <p class="card-text pb-0 px-2">Father's Name: </p>
                    <p class="card-text pb-0 px-2" style="word-wrap: break-word; white-space: normal; line-height: 1.3;">Address: </p>
                    <p class="card-text pb-2 px-2">Contact: </p>
                    <p class="card-text pb-0 px-2">Valid Upto :</p>
                </div>
                <div style="position: relative; height: 100vh;">
                    <div style="position: absolute; bottom: 0; right: 0; margin-top: 10px;">
                        <img src="$principalSign" alt="Principal Sign" style="width: 60px; height: 30px;">
                    </div>
                </div>
            </div>
          HTML;
        } elseif ($layoutType === 'Teacher') {
          echo <<<HTML
                    <p id="staffCard" class="card-text pb-2"
                      style="position: absolute; top: 58%; left: 60px; transform: rotate(-90deg); font-size: 1.5rem; color: #666666; line-height: 0.8; transform-origin: left center;">
                      Staff ID Card
                    </p>
                    <h3 class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 2px;">Teacher Name</h3>
                    <h3 class="card-title" style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">Designation</h3>
                </div>
                <div class="card-overlay">
                    <h3 style="font-size: 1.2rem; font-weight: bold; color: white;">$layoutName</h3>
                    <div>
                        <button class="btn btn-primary edit-button" data-id="$id" style="margin: 5px;">Edit Layout</button>
                        <button class="btn btn-secondary teacher-fill-button" data-id="$id" style="margin: 5px;">Fill Details</button>
                        <button class="btn btn-danger delete-button" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal" 
                            data-layout-name="$layoutName">
                            Delete
                        </button>
                    </div>
                </div>
                <div class="card-details" style="font-size: 0.8rem; font-weight:bold; color: #666666; line-height: 0.1; padding: 10px; border-radius: 8px;">
                    <p class="card-text pb-1 px-2">Date of Birth: </p>
                    <p class="card-text pb-1 px-2">Blood Group: </p>
                    <p class="card-text pb-0 px-2" style="word-wrap: break-word; white-space: normal; line-height: 1.3;">Address: </p>
                    <p class="card-text pb-2 px-2">Contact: </p>
                    <p class="card-text pb-0 px-2">Valid Upto :</p>
                </div>
                <div style="position: relative; height: 100vh;">
                    <div style="position: absolute; bottom: 0; right: 0; margin-top: 10px;">
                        <img src="$principalSign" alt="Principal Sign" style="width: 60px; height: 30px;">
                    </div>
                </div>
            </div>
          HTML;
        }
      }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
    ?>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        const layoutName = button.getAttribute('data-layout-name');
        document.getElementById('deletelayoutName').value = layoutName;
        document.getElementById('layoutToDelete').textContent = layoutName;
      });
    });
  });

  document.getElementById("deleteForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch("backend.php", {
        method: "POST",
        body: formData,
      })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(data.message);
          location.reload();
        } else {
          alert(data.error);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred while processing the request.");
      });
  });

  document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.edit-button');

    editButtons.forEach(button => {
      button.addEventListener('click', () => {
        const layoutId = button.getAttribute('data-id');
        window.location.href = `edit/?id=${layoutId}`;
      });
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.student-fill-button');

    editButtons.forEach(button => {
      button.addEventListener('click', () => {
        const layoutId = button.getAttribute('data-id');
        window.location.href = `populate/student/?id=${layoutId}`;
      });
    });
  });
  document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.teacher-fill-button');

    editButtons.forEach(button => {
      button.addEventListener('click', () => {
        const layoutId = button.getAttribute('data-id');
        window.location.href = `populate/teacher/?id=${layoutId}`;
      });
    });
  });
</script>



<?php include("includes/footer.php"); ?>