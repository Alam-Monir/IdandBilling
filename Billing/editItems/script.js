document.getElementById('createForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch('create.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            alert(data);
            window.location.reload();
        })
        .catch(error => {
            alert('Error: ' + error);
        });
});

document.getElementById('deleteForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch('create.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            alert(data);
            window.location.reload();
        })
        .catch(error => {
            alert('Error: ' + error);
        });
});

document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const itemName = button.getAttribute('data-item-name');
            const itemId = button.getAttribute('data-item-id');
            document.getElementById('itemIdToDelete').value = itemId;
            document.getElementById('modal-item-name').textContent = itemName;
        });
    });
});

document.getElementById("deleteFormModal").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch("edit.php", {
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

document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-button');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const itemId = button.getAttribute('data-item-id');
            const itemName = button.getAttribute('data-item-name');
            const itemPrice = button.getAttribute('data-item-price');

            document.getElementById('itemIdToEdit').value = itemId;
            document.getElementById('edit-modal-item-name').textContent = itemName;
            document.getElementById('itemNameToEdit').value = itemName;
            document.getElementById('itemPriceToEdit').value = itemPrice;
        });
    });
});

document.getElementById('editFormModal').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    fetch('edit.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
});
