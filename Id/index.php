<?php
include("includes/header.php");
include("includes/nav.php");
?>
<div class="d-flex justify-content-around">
    <div id="cardLayout"
        class="card mx-auto d-flex flex-column"
        style="width: 400px; height: 600px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden; background-color: #ffffff; position: relative; transition: all 0.3s ease;">
        
        <!-- Card content here (existing elements) -->
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 10px;">
            <img id="logo" src="img/img_placeholder.png" alt="School Logo" style="width: 80px; height: 80px;">
            <h3 id="SchoolName" class="card-title mt-2" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">School Name</h3>
            <p id="SchoolAddress" class="card-text pb-2" style="font-size: 0.8rem; color: #666666; line-height: 0.8;">
                School Address
            </p>
            <img id="profileImg" src="img/profileImage.jpg" alt="Student Image" style="width: 140px; height: 160px;">
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

        <!-- Button container (for Edit and Fill Data) -->
        <div id="buttonContainer" class="d-flex justify-content-center align-items-center"
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); gap: 10px; visibility: hidden;">
            <button id="editBtn" class="btn btn-primary">Edit</button>
            <button id="fillDataBtn" class="btn btn-primary">Fill Data</button>
        </div>

        <!-- Bottom signature -->
        <div style="position: relative; height: 100vh;">
            <div style="position: absolute; bottom: 0; right: 0; margin-top: 10px;">
                <img id="sign" src="img/img_placeholder.png" alt="Principal Sign" style="width: 60px; height: 30px;">
            </div>
        </div>
    </div>
</div>

<!-- Inline CSS for hover effect -->
<script>
    document.getElementById('cardLayout').addEventListener('mouseenter', function() {
        document.getElementById('buttonContainer').style.visibility = 'visible';
    });
    document.getElementById('cardLayout').addEventListener('mouseleave', function() {
        document.getElementById('buttonContainer').style.visibility = 'hidden';
    });
</script>


<?php include("includes/footer.php") ?>