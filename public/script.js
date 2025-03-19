function loadImage(event) {
    const img = document.getElementById('image');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.onload = function() {
        img.style.display = 'block';
        img.style.width = ''; // Reset width
        img.style.height = ''; // Reset height
    };
}

function adjustPadding() {
    const top = parseInt(document.getElementById('top').value) || 0;
    const left = parseInt(document.getElementById('left').value) || 0;
    const right = parseInt(document.getElementById('right').value) || 0;
    const bottom = parseInt(document.getElementById('bottom').value) || 0;
    const imgCell = document.getElementById('image-cell');
    imgCell.style.padding = `${top}px ${right}px ${bottom}px ${left}px`;
}

function toggleOverlay() {
    const overlay = document.getElementById('overlay');
    overlay.style.display = document.getElementById('toggle-overlay').checked ? 'block' : 'none';
}

function uploadImage() {
    const top = document.getElementById('top').value;
    const left = document.getElementById('left').value;
    const right = document.getElementById('right').value;
    const bottom = document.getElementById('bottom').value;
    const fileInput = document.getElementById('file-input');
    if (fileInput.files.length === 0) {
        alert('Please select an image file to upload.');
        return;
    }
    const formData = new FormData();
    formData.append('image', fileInput.files[0]);
    
    // Build the URL with the padding parameters
    const url = `process.php?top=${top}&left=${left}&right=${right}&bottom=${bottom}`;

    // Use fetch to upload the image and get the processed image back
    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        // Check if the response is OK (status code 200)
        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'padded_image.webp';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        alert('Image uploaded and processed successfully.');
    })
    .catch(error => alert('Error uploading image: ' + error));
} 


