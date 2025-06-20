<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // Retrieve padding values
    $top = isset($_GET['top']) ? (int)$_GET['top'] : 0;
    $left = isset($_GET['left']) ? (int)$_GET['left'] : 0;
    $right = isset($_GET['right']) ? (int)$_GET['right'] : 0;
    $bottom = isset($_GET['bottom']) ? (int)$_GET['bottom'] : 0;

    // Get uploaded file info
    $uploadedFile = $_FILES['image'];
    $filePath = $uploadedFile['tmp_name'];

    // Check if the uploaded file exists
    if (!file_exists($filePath)) {
        die('File upload error.');
    }

    // Determine the image type and create an image resource accordingly
    $imageType = exif_imagetype($filePath);
    switch ($imageType) {
        case IMAGETYPE_PNG:
            $originalImage = imagecreatefrompng($filePath);
            break;
        case IMAGETYPE_WEBP:
            $originalImage = imagecreatefromwebp($filePath);
            break;
        default:
            die('Unsupported image type.');
    }

    // Get original image dimensions
    $originalWidth = imagesx($originalImage);
    $originalHeight = imagesy($originalImage);

    // Calculate new dimensions
    $newWidth = $originalWidth + $left + $right;
    $newHeight = $originalHeight + $top + $bottom;

    // Create a new truecolor image with a transparent background
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    $transparentColor = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
    imagefill($newImage, 0, 0, $transparentColor);
    imagealphablending($newImage, true);
    imagesavealpha($newImage, true);

    // Copy the original image onto the new image at the correct position
    imagecopy($newImage, $originalImage, $left, $top, 0, 0, $originalWidth, $originalHeight);

    // Set the header to indicate a .png image
    header('Content-Type: image/png');

    // Output the new image as a png file
    imagepng($newImage);

    // Free up memory
    imagedestroy($originalImage);
    imagedestroy($newImage);
} else {
    echo 'Invalid request.';
}
