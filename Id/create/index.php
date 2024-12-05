<?php
include("../includes/header.php");
include("../includes/nav.php");
?>

<div class="d-flex justify-content-around">
    <!-- Student Id Card -->
    <div id="studentCardLayout"
        class="card mx-auto flex-column"
        style="width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden; background-color: #ffffff; display: none;">
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 10px;">
            <img id="logo" src="../img/img_placeholder.png" alt="School Logo" style="width: 80px; height: 80px;">
            <h3 id="studentSchoolName" class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">School Name</h3>
            <p id="studentSchoolAddress" class="card-text pb-2" style="font-size: 0.8rem; color: #666666; line-height: 0.8;">
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
            <p id="validUpto" class="card-text pb-0 px-2">
                Valid Upto :
            </p>
        </div>
        <div style="position: relative; height: 100vh;">
            <div style="position: absolute; bottom: 0; right: 0; margin-top: 10px;">
                <img id="sign" src="../img/img_placeholder.png" alt="Principal Sign" style="width: 60px; height: 30px;">
            </div>
        </div>
    </div>

    <!-- Teacher Id Card -->
    <div id="teacherCardLayout"
        class="card mx-auto flex-column"
        style="width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden; background-color: #ffffff; display: none;">
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 10px; position: relative;">
            <img id="logo" src="../img/img_placeholder.png" alt="School Logo" style="width: 80px; height: 80px;">
            <h3 id="teacherSchoolName" class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">School Name</h3>
            <p id="teacherSchoolAddress" class="card-text pb-2" style="font-size: 0.8rem; color: #666666; line-height: 0.8;">
                School Address
            </p>
            <p id="staffCard" class="card-text pb-2"
                style="position: absolute; top: 75%; left: 60px; transform: rotate(-90deg); font-size: 1.5rem; color: #666666; line-height: 0.8; transform-origin: left center;">
                Staff ID Card
            </p>
            <img id="profileImg" src="../img/profileImage.jpg" alt="Student Image"
                style="width: 140px; height: 160px;">
            <h3 id="StudentName" class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 2px;">Name</h3>
            <h3 id="StudentClass" class="card-title" style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">Designation</h3>
        </div>
        <div id="details" style="font-size: 0.8rem; font-weight:bold; color: #666666; line-height: 0.1;">
            <p id="dob" class="card-text pb-1 px-2">
                Date of Birth :
            </p>
            <p id="bGroup" class="card-text px-2">
                Blood Group :
            </p>
            <p id="add" class="card-text pb-0 px-2" style="word-wrap: break-word; white-space: normal; line-height: 1.3;">
                Address : Jirania, Joynagar, Delhiwala PetrolPump, West Tripura, 799045
            </p>
            <p id="phNo" class="card-text pb-2 px-2">
                Contact :
            </p>
            <p id="validUpto" class="card-text pb-0 px-2">
                Valid Upto :
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
        style="max-width: 400px; height: 620px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow-y: auto; background-color: #ffffff;">
        <form id="layoutForm" action="saveLayout.php" method="POST" enctype="multipart/form-data">
            <div class="input-group mb-3">
                <select class="form-select" id="layoutType" name="layoutType" required>
                    <option value="Student" selected>Student</option>
                    <option value="Teacher">Teacher</option>
                </select>
            </div>
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
    document.addEventListener('DOMContentLoaded', () => {
        const layoutType = document.getElementById('layoutType');
        const studentCardLayout = document.getElementById('studentCardLayout');
        const teacherCardLayout = document.getElementById('teacherCardLayout');

        const toggleCards = (layout) => {
            if (layout === 'Student') {
                studentCardLayout.style.display = 'flex';
                teacherCardLayout.style.display = 'none';
            } else if (layout === 'Teacher') {
                studentCardLayout.style.display = 'none';
                teacherCardLayout.style.display = 'flex';
            } else {
                studentCardLayout.style.display = 'none';
                teacherCardLayout.style.display = 'none';
            }
        };

        const savedLayoutType = sessionStorage.getItem('layoutType');

        if (savedLayoutType) {
            layoutType.value = savedLayoutType;
            toggleCards(savedLayoutType);
        } else {
            sessionStorage.setItem('layoutType', 'Student');
            layoutType.value = 'Student';
            toggleCards('Student');
        }

        layoutType.addEventListener('change', () => {
            const selectedLayout = layoutType.value;
            sessionStorage.setItem('layoutType', selectedLayout);
            window.location.reload();
        });

        const schoolNameInput = document.getElementById('scNameInput');
        schoolNameInput.addEventListener('input', () => {
            if (layoutType.value === 'Student') {
                document.getElementById('studentSchoolName').textContent = schoolNameInput.value;
            } else if (layoutType.value === 'Teacher') {
                document.getElementById('teacherSchoolName').textContent = schoolNameInput.value;
            }
        });

        const schoolAddressInput = document.getElementById('scAddressInput');
        schoolAddressInput.addEventListener('input', () => {
            if (layoutType.value === 'Student') {
                document.getElementById('studentSchoolAddress').textContent = schoolAddressInput.value;
            } else if (layoutType.value === 'Teacher') {
                document.getElementById('teacherSchoolAddress').textContent = schoolAddressInput.value;
            }
        });

        document.getElementById('bgImageInput').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const layout = layoutType.value === 'Student' ? studentCardLayout : teacherCardLayout;
                    layout.style.backgroundImage = `url(${e.target.result})`;
                    layout.style.backgroundSize = 'cover';
                    layout.style.backgroundPosition = 'center';
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('logoInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const logoElement = layoutType.value === 'Student' ?
                        studentCardLayout.querySelector('#logo') :
                        teacherCardLayout.querySelector('#logo');
                    logoElement.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('signInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const signElement = layoutType.value === 'Student' ?
                        studentCardLayout.querySelector('#sign') :
                        teacherCardLayout.querySelector('#sign');
                    signElement.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });


    document.getElementById('layoutForm').addEventListener('submit', function(event) {
        event.preventDefault();

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
                    form.reset();
                } else {
                    alert(`Error: ${data.message}`);
                }

                window.location.reload();
            })
            .catch(error => {
                alert(`Error: ${error.message}`);
            });
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('layoutForm').reset();
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        window.location.href = '../';
    });
</script>


<?php include("../includes/footer.php") ?>