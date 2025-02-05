<?php include('../includes/header.php') ?>
<?php include('../includes/nav.php') ?>
<?php include ('../../auth/auth_check_admin.php'); ?>

<div class="d-flex justify-content-center mt-3">
  <div class="w-50 p-5 ml-5 shadow bg-body-tertiary">
    <form id="sellerInfo" class="rounded-3">
      <div class="d-flex justify-content-center fw-bold">Edit Seller Info</div>
      <div class="form-floating">
        <input type="text" id="sellerName" class="form-control mt-5" placeholder="Seller Name">
        <label for="sellerName">Seller Name</label>
      </div>
      <div class="form-floating">
        <input type="text" id="sellerContact" class="form-control mt-3" placeholder="Contact">
        <label for="sellerContact">Contact</label>
      </div>
      <div class="form-floating">
        <input type="email" id="email" class="form-control mt-3" placeholder="email">
        <label for="email">Email</label>
      </div>
      <div class="form-floating">
        <input type="text" id="gstNo" class="form-control mt-3" placeholder="">
        <label for="gstNo">GST No.</label>
      </div>
      <div class="form-floating">
        <textarea class="form-control mt-3" id="sellerAddress" placeholder="Address" style="height: 100px"></textarea>
        <label for="sellerAddress">Address</label>
      </div>
      <div class="d-flex justify-content-sm-evenly mt-3">
        <button type="button" class="btn btn-danger" id="cancelBtn">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveBtn" disabled>Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const sellerName = document.getElementById('sellerName');
    const contact = document.getElementById('sellerContact');
    const email = document.getElementById('email');
    const gstNo = document.getElementById('gstNo');
    const address = document.getElementById('sellerAddress');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    let initialData = {};

    fetch('sellerInfo.php', {
        method: 'GET'
      })
      .then(response => response.json())
      .then(data => {
        if (data) {
          sellerName.value = data.sellerName || '';
          contact.value = data.contact || '';
          email.value = data.email || '';
          gstNo.value = data.gstNo || '';
          address.value = data.address || '';

          initialData = {
            sellerName: sellerName.value,
            contact: contact.value,
            email: email.value,
            gstNo: gstNo.value,
            address: address.value
          };
        }

        toggleSaveButton();
      });

    function hasChanges() {
      return (
        sellerName.value !== initialData.sellerName ||
        contact.value !== initialData.contact ||
        contact.email !== initialData.email ||
        gstNo.value !== initialData.gstNo ||
        address.value !== initialData.address
      );
    }

    function toggleSaveButton() {
      saveBtn.disabled = !hasChanges();
    }

    [sellerName, contact, email, gstNo, address].forEach(field => {
      field.addEventListener('input', toggleSaveButton);
    });

    saveBtn.addEventListener('click', function() {
      const formData = new FormData();
      formData.append('sellerName', sellerName.value);
      formData.append('contact', contact.value);
      formData.append('email', email.value);
      formData.append('gstNo', gstNo.value);
      formData.append('address', address.value);

      fetch('sellerInfo.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(result => {
          if (result.status === 'inserted') {
            alert('Data inserted successfully.');
          } else if (result.status === 'updated') {
            alert('Data updated successfully.');
          }

          initialData = {
            sellerName: sellerName.value,
            contact: contact.value,
            email: email.value,
            gstNo: gstNo.value,
            address: address.value
          };

          toggleSaveButton();
        });
    });

    cancelBtn.addEventListener('click', function() {
      sellerName.value = '';
      contact.value = '';
      email.value = '';
      gstNo.value = '';
      address.value = '';
      toggleSaveButton();
    });
  });
</script>

<?php include('../includes/footer.php') ?>