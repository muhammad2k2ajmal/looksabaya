<?php
function resize2($width, $height, $tempName, $destinationPath) {
    // Get original dimensions
    list($originalWidth, $originalHeight) = getimagesize($tempName);
    $image = imagecreatefromstring(file_get_contents($tempName));
    $resizedImage = imagecreatetruecolor($width, $height);

    // Resize the image
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

    // Save the resized image
    switch (pathinfo($destinationPath, PATHINFO_EXTENSION)) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($resizedImage, $destinationPath);
            break;
        case 'png':
            imagepng($resizedImage, $destinationPath);
            break;
        case 'gif':
            imagegif($resizedImage, $destinationPath);
            break;
        default:
            return false; // Unsupported format
    }

    // Clean up
    imagedestroy($image);
    imagedestroy($resizedImage);
    
    return true;
}
?>
