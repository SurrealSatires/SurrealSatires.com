<?php
// Save uploaded files to this directory
$upload_dir = '/var/www/uploads/';

// Create directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Process file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $target_path = $upload_dir . basename($file['name']);
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Log successful upload
        $log_entry = sprintf(
            "[%s] File '%s' uploaded from %s\n",
            date('Y-m-d H:i:s'),
            $file['name'],
            $_SERVER['REMOTE_ADDR']
        );
        file_put_contents($upload_dir . 'exfil.log', $log_entry, FILE_APPEND);
    }
}
?>
