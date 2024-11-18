/* script.js */
const inputField = document.getElementById('draggable-input');
const container = document.getElementById('draggable-input-container');
const imageContainer = document.getElementById('image-container');
const draggableImage = document.getElementById('draggable-image');

// Input field controls
const inputResizeSlider = document.getElementById('input-resize-slider');
const inputSizeDisplay = document.getElementById('input-size-display');

// Image controls
const imageWidthInput = document.getElementById('image-width');
const imageHeightInput = document.getElementById('image-height');
const resizeImageButton = document.getElementById('resize-image-btn');

// Image upload
const imageUploadInput = document.getElementById('image-upload');

let isDraggingInput = false;
let isDraggingImage = false;
let inputOffsetX, inputOffsetY, imageOffsetX, imageOffsetY;

// Dragging functionality for the input field
inputField.addEventListener('mousedown', (e) => {
    isDraggingInput = true;
    inputOffsetX = e.clientX - container.getBoundingClientRect().left;
    inputOffsetY = e.clientY - container.getBoundingClientRect().top;

    inputField.style.cursor = 'grabbing';
});

// Dragging functionality for the image container
imageContainer.addEventListener('mousedown', (e) => {
    isDraggingImage = true;
    imageOffsetX = e.clientX - imageContainer.getBoundingClientRect().left;
    imageOffsetY = e.clientY - imageContainer.getBoundingClientRect().top;

    imageContainer.style.cursor = 'grabbing';
});

// Handle mousemove for dragging
document.addEventListener('mousemove', (e) => {
    if (isDraggingInput) {
        let newX = e.clientX - inputOffsetX;
        let newY = e.clientY - inputOffsetY;

        // Ensure input field stays within bounds
        newX = Math.max(0, Math.min(newX, window.innerWidth - container.offsetWidth));
        newY = Math.max(0, Math.min(newY, window.innerHeight - container.offsetHeight));

        container.style.left = `${newX}px`;
        container.style.top = `${newY}px`;
    }

    if (isDraggingImage) {
        let newX = e.clientX - imageOffsetX;
        let newY = e.clientY - imageOffsetY;

        // Ensure image container stays within bounds
        newX = Math.max(0, Math.min(newX, window.innerWidth - imageContainer.offsetWidth));
        newY = Math.max(0, Math.min(newY, window.innerHeight - imageContainer.offsetHeight));

        imageContainer.style.left = `${newX}px`;
        imageContainer.style.top = `${newY}px`;
    }
});

// Stop dragging on mouseup
document.addEventListener('mouseup', () => {
    isDraggingInput = false;
    isDraggingImage = false;

    inputField.style.cursor = 'grab';
    imageContainer.style.cursor = 'grab';
});

// Resize the input field with the slider
inputResizeSlider.addEventListener('input', () => {
    const newWidth = inputResizeSlider.value;
    container.style.width = `${newWidth}px`;
    inputField.style.width = `${newWidth}px`;

    inputSizeDisplay.textContent = `Width: ${newWidth}px`;
});

// Resize the image using width and height inputs
resizeImageButton.addEventListener('click', () => {
    const newWidth = imageWidthInput.value;
    const newHeight = imageHeightInput.value;

    imageContainer.style.width = `${newWidth}px`;
    imageContainer.style.height = `${newHeight}px`;
});

// Handle image upload and fit to container
imageUploadInput.addEventListener('change', (event) => {
    const file = event.target.files[0];

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();

        reader.onload = (e) => {
            draggableImage.src = e.target.result;

            // Ensure the uploaded image fits the container
            draggableImage.style.objectFit = 'cover'; // Adjusts image to fill the container proportionally
        };

        reader.readAsDataURL(file);
    } else {
        alert('Please upload a valid image file.');
    }
});

