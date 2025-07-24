<?php

// Function to resize and compress image 
function resize($width, $height, $dest, $tmp_name, $original_name) {
    /* Get original image x y */
    list($w, $h) = getimagesize($tmp_name);
    
    /* Calculate new image size with ratio */
    $ratio = max($width / $w, $height / $h);
    $h = (int)ceil($height / $ratio);
    $x = (int)(($w - $width / $ratio) / 2);
    $w = (int)ceil($width / $ratio);

    /* New file name */
    $filename = time() . "-" . str_replace(' ', '-', $original_name); // Using timestamp and replacing spaces

    /* Construct destination path */
    $path = $dest . $filename;

    /* Read binary data from image file */
    $imgString = file_get_contents($tmp_name);

    /* Create image from string */
    $image = imagecreatefromstring($imgString);
    $tmp = imagecreatetruecolor($width, $height);

    /* Preserve transparency for PNG and GIF images */
    if ($_FILES['image']['type'] == 'image/png' || $_FILES['image']['type'] == 'image/gif') {
        imagealphablending($tmp, false);
        imagesavealpha($tmp, true);
        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
        imagefilledrectangle($tmp, 0, 0, $width, $height, $transparent);
    }

    /* Resize image */
    imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);

    /* Save image */
    switch ($_FILES['image']['type']) {
        case 'image/jpeg':
            imagejpeg($tmp, $path, 100);
            break;
        case 'image/png':
            imagepng($tmp, $path, 0);
            break;
        case 'image/gif':
            imagegif($tmp, $path);
            break;
        case 'image/webp':
            imagewebp($tmp, $path, 100);
            break;
        default:
            exit;
    }

    /* Compress image if needed */
    $maxFileSize = 2 * 1024 * 1024; // 2 MB
    if (filesize($path) > $maxFileSize) {
        compressImage($path, $path, 75); // Adjust quality as needed
    }

    /* Cleanup memory */
    imagedestroy($image);
    imagedestroy($tmp);

    return $filename;
}

// Function to compress image
function compressImage($source, $destination, $quality) {
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);
    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);
    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);
    elseif ($info['mime'] == 'image/webp') 
        $image = imagecreatefromwebp($source);
    
    // Save compressed image
    imagejpeg($image, $destination, $quality);
}

?>