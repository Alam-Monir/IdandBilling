<?php
include("../includes/header.php");
include("../includes/nav.php");
?>
<?php
include('../../config/dbcon.php');

function prepareImageUrl($imageUrl)
{
    $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $imageUrl);
    return htmlspecialchars("" . $imageUrl);
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM idLayout WHERE id = :id");
$stmt->execute(['id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$schoolLogo = prepareImageUrl($row['logo']);
$principalSign = prepareImageUrl($row['sign']);
$bgImage = prepareImageUrl($row['bgImage']);

$id = htmlspecialchars($row['id']);
$schoolName = htmlspecialchars($row['schoolName']);
$schoolAddress = htmlspecialchars($row['schoolAdd']);
$layoutName = htmlspecialchars($row['layoutName']);
?>


<div class="d-flex justify-content-around">
    <!-- Id Card -->
    <div id="cardLayout"
        class="card mx-auto d-flex flex-column"
        style="width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden; background-color: #ffffff; background-image: url('<?= $bgImage ?>'); background-size: cover; background-position: center;">
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 10px;">
            <img id="logo" src="<?= $schoolLogo ?>" alt="School Logo" style="width: 80px; height: 80px;">
            <h3 id="SchoolName" class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">
                <?= $schoolName ?>
            </h3>
            <p id="SchoolAddress" class="card-text pb-2" style="font-size: 0.8rem; color: #666666; line-height: 0.8;">
                <?= $schoolAddress ?>
            </p>
            <img id="profileImg" src="../img/profileImage.jpg" alt="Student Image" style="width: 140px; height: 160px;">
            <h3 id="StudentName" class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 2px;">
                Name
            </h3>
            <h3 id="StudentClass" class="card-title" style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">
                Class/Designation
            </h3>
        </div>
        <div id="details" style="font-size: 0.8rem; font-weight:bold; color: #666666; line-height: 0.1;">
            <p id="dob" class="card-text pb-1 px-2">
                Date of Birth:
            </p>
            <p id="bGroup" class="card-text pb-1 px-2">
                Blood Group:
            </p>
            <p id="father" class="card-text pb-0 px-2">
                Father's Name:
            </p>
            <p id="add" class="card-text pb-0 px-2" style="word-wrap: break-word; white-space: normal; line-height: 1.3;">
                Address: Jirania, Joynagar, Delhiwala PetrolPump, West Tripura, 799045
            </p>
            <p id="phNo" class="card-text pb-2 px-2">
                Contact:
            </p>
        </div>
        <div style="position: relative; height: 100vh;">
            <div style="position: absolute; bottom: 0; right: 0; margin-top: 10px;">
                <img id="sign" src="<?= $principalSign ?>" alt="Principal Sign" style="width: 60px; height: 30px;">
            </div>
        </div>
    </div>



    <!-- Edit Modal -->
    <div
        class="card mx-auto d-flex flex-column"
        style="max-width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow-y: auto; background-color: #ffffff;">
        <form id="layoutForm" action="editLayout.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="layoutId" value="<?= $id ?>">
            <div class="form-floating mb-3">
                <input type="text" id="layoutName" class="form-control" name="layoutName" placeholder="" value="<?= $layoutName ?>" required>
                <label for="floatingInput">Enter Layout Name</label>
            </div>
            <div class="section mb-3">
                <h5>Upload Background Image:</h5>
                <input type="file" id="bgImageInput" name="bgImage" accept="image/*" alt="bgImage">
            </div>
            <div class="section mb-3">
                <h5>Upload School Logo:</h5>
                <div>
                    <input type="file" id="logoInput" name="schoolLogo" accept="image/*">
                </div>
            </div>
            <div class="section mb-3">
                <h5>Upload Principal Signature:</h5>
                <div>
                    <input type="file" id="signInput" name="principalSign" alt="prImage" accept="image/*">
                </div>
            </div>
            <div class="form-floating mb-3">
                <input type="text" id="scNameInput" class="form-control" name="schoolName" placeholder="" value="<?= $schoolName ?>" required>
                <label for="scNameInput">Enter School Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" id="scAddressInput" class="form-control" name="schoolAdd" placeholder="" value="<?= $schoolAddress ?>" required>
                <label for="scAddressInput">Enter School Address</label>
            </div>
            <div class="d-flex justify-content-evenly">
                <button type="button" class="btn btn-outline-danger mt-2" id="cancelButton">Cancel</button>
                <button type="submit" class="btn btn-outline-primary mt-2" id="saveButton" disabled>Save</button>
            </div>
        </form>
        <div id="alertMessage" class="alert" style="display: none;"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('layoutForm');
        const saveButton = document.getElementById('saveButton');

        form.addEventListener('input', () => {
            saveButton.disabled = false;
        });

        form.addEventListener('submit', function(event) {});
    });

    document.getElementById('layoutForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert(`Error: ${result.message}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while submitting the form.');
        }
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        window.location.href = '../';
    });
</script>

<?php include("../includes/footer.php") ?>