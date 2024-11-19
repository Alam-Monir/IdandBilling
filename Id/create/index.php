<?php
include("../includes/header.php");
include("../includes/nav.php");
?>
<!-- <div class="p-2">
    Create Modal
</div> -->
<div class="d-flex justify-content-around">
    <div id="cardLayout"
        class="card mx-auto d-flex flex-column "
        style="width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden; background-color: #ffffff;">
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
            <img src="../img/skmlogo.png" alt="Image Placeholder" style="width: 80px; height: 80px; ">
            <h3 class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">Sri Krishna Mission School</h3>
            <p class="card-text" style="font-size: 0.8rem; color: #666666; line-height: 0.8;">
                Bholananda Palli, Airport Road, Agartala, Tripura West, 799045
            </p>
        </div>
    </div>
    <div
        class="card mx-auto d-flex flex-column"
        style="max-width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow-y: auto; background-color: #ffffff;">
        <div>
            <div class="form-floating mb-3">
                <input type="text" id="layoutName" class="form-control" name="layoutName" placeholder="" required>
                <label for="floatingInput">Enter Layout Name</label>
            </div>
            <div class="section mb-3">
                <h5>Upload Background Image:</h5>
                <input type="file" id="bgImageInput" accept="image/*" alt="bgImage">
            </div>
            <div class="section mb-3">
                <h5>Upload School logo:</h5>
                <div>
                    <input type="file" alt="ScLogo">
                    <button class="btn btn-outline-primary mt-2">Upload</button>
                </div>
                <div class="pt-2">
                    <label for="logoRange" class="form-label">Resize Logo</label>
                    <input type="range" class="form-range" id="customRange1">
                </div>
            </div>
            <div class="section mb-3">
                <h5>Upload Principal Signature:</h5>
                <div>
                    <input type="file" alt="prImage">
                    <button class="btn btn-outline-primary mt-2">Upload</button>
                </div>
                <div class="pt-2">
                    <label for="signRange" class="form-label">Resize Signature</label>
                    <input type="range" class="form-range" id="customRange1">
                </div>
            </div>
            <div class="form-floating mb-3">
                <input type="text" id="scName" class="form-control" name="school-Name" placeholder="" required>
                <label for="floatingInput">Enter School Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" id="scAdd" class="form-control" name="school-address" placeholder="" required>
                <label for="floatingInput">Enter School Address</label>
            </div>
        </div>
    </div>

</div>

<script>
    document.getElementById('bgImageInput').addEventListener('change', function() {
        const file = this.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create a FileReader to read the file

            reader.onload = function(e) {
                const cardLayout = document.getElementById('cardLayout');
                cardLayout.style.backgroundImage = `url(${e.target.result})`; // Set background image
                cardLayout.style.backgroundSize = 'cover'; // Ensure image covers the card
                cardLayout.style.backgroundPosition = 'center'; // Center the image
                cardLayout.style.backgroundRepeat = 'no-repeat'; // Prevent repetition
            };

            reader.readAsDataURL(file); // Read the file as a Data URL
        }
    });
</script>

<?php include("../includes/footer.php") ?>