<?php
include("../includes/header.php");
include("../includes/nav.php");
?>

<div class="d-flex justify-content-around">
    <!-- Id Card -->
    <div id="cardLayout"
        class="card mx-auto d-flex flex-column"
        style="width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden; background-color: #ffffff;">
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 10px;">
            <img id="logo" src="../img/img_placeholder.png" alt="School Logo" style="width: 80px; height: 80px;">
            <h3 id="SchoolName" class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">School Name</h3>
            <p id="SchoolAddress" class="card-text pb-2" style="font-size: 0.8rem; color: #666666; line-height: 0.8;">
                School Address
            </p>
            <img id="profileImg" src="../img/profileImage.jpg" alt="Student Image" style="width: 140px; height: 160px; ">
            <h3 id="StudentName" class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 2px;">Name</h3>
            <h3 id="StudentClass" class="card-title" style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">Class</h3>

        </div>
        <div id="details" style="font-size: 0.8rem; font-weight:bold; color: #666666; line-height: 0.1;">
            <p id="dob" class="card-text pb-1 px-2">
                Date of Birth :
            </p>
            <p id="bGroup" class="card-text pb-1 px-2">
                Blood Group :
            </p>
            <p id="father" class="card-text pb-0 px-2">
                Father's Name :
            </p>
            <p id="add" class="card-text pb-0 px-2" style="word-wrap: break-word; white-space: normal; line-height: 1.3;">
                Address : Jirania, Joynagar, Delhiwala PetrolPump, West Tripura, 799045
            </p>
            <p id="phNo" class="card-text pb-2 px-2">
                Contact :
            </p>
        </div>
        <div style="position: relative; height: 100vh;">
            <div style="position: absolute; bottom: 0; right: 0; margin-top: 10px;">
                <img id="sign" src="../img/img_placeholder.png" alt="Principal Sign" style="width: 60px; height: 30px;">
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div
        class="card mx-auto d-flex flex-column"
        style="max-width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow-y: auto; background-color: #ffffff;">
        <form id="layoutForm" action="saveLayout.php" method="POST" enctype="multipart/form-data">
            <div class="form-floating mb-3">
                <input type="text" id="layoutName" class="form-control" name="layoutName" placeholder="" required>
                <label for="floatingInput">Enter Layout Name</label>
            </div>
            <div class="section mb-3">
                <h5>Upload Background Image:</h5>
                <input type="file" id="bgImageInput" name="bgImage" accept="image/*" alt="bgImage">
            </div>
            <div class="section mb-3">
                <h5>Upload School logo:</h5>
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
                <input type="text" id="scNameInput" class="form-control" name="schoolName" placeholder="" required>
                <label for="scNameInput">Enter School Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" id="scAddressInput" class="form-control" name="schoolAddress" placeholder="" required>
                <label for="scAddressInput">Enter School Address</label>
            </div>
            <div class="d-flex justify-content-evenly">
                <button type="button" class="btn btn-outline-danger mt-2" id="cancelButton">Cancel</button>
                <button type="submit" class="btn btn-outline-primary mt-2" id="saveButton">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('bgImageInput').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const cardLayout = document.getElementById('cardLayout');
                cardLayout.style.backgroundImage = `url(${e.target.result})`;
                cardLayout.style.backgroundSize = 'cover';
                cardLayout.style.backgroundPosition = 'center';
                cardLayout.style.backgroundRepeat = 'no-repeat';
            };

            reader.readAsDataURL(file);
        }
    });
    document.getElementById('logoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('signInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('sign').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    const schoolNameInput = document.getElementById('scNameInput');
    const schoolAddressInput = document.getElementById('scAddressInput');
    const schoolNameDisplay = document.getElementById('SchoolName');
    const schoolAddressDisplay = document.getElementById('SchoolAddress');

    schoolNameInput.addEventListener('input', function() {
        schoolNameDisplay.textContent = this.value || "School Name";
    });

    schoolAddressInput.addEventListener('input', function() {
        schoolAddressDisplay.textContent = this.value || "School Address";
    });
</script>
<script>
    document.getElementById('layoutForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission behavior

        const form = document.getElementById('layoutForm');
        const formData = new FormData(form);

        fetch('saveLayout.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Layout saved successfully!');
                    form.reset(); // Reset the form after successful submission
                } else {
                    alert(`Error: ${data.message}`);
                }

                window.location.reload(); // Optionally reload the page to reflect changes
            })
            .catch(error => {
                alert(`Error: ${error.message}`);
            });
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('layoutForm').reset(); // Reset the form on cancel
    });
</script>


<?php include("../includes/footer.php") ?>