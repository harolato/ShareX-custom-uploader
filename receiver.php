<?php

$PASSWORD = 'SECRET';
$MAX_LIFETIME = 12; // Months

if ( $_POST['password'] != $PASSWORD ){
    http_response_code(404);
    exit('Unauthorized');
}
$f = $_FILES['file'];


/* Cleanup old directories */
$dirs = glob('folio/albums/*');
$now = new DateTime();
foreach ($dirs as $dir) {
    $dir_c_time = new DateTime();
    $dir_c_time->setTimestamp(filectime($dir));

    if ( ($now->diff($dir_c_time)->m +1) > $MAX_LIFETIME ) {
        rrmdir($dir);
    }

}
/* ======================= */

$folder = date("Y-m");

if ( !mkdir("folio/albums/" . $folder) ) {
    mkdir("folio/albums/" . $folder);
}

$ext = pathinfo($f['name'], PATHINFO_EXTENSION);
if ( in_array(strtolower($ext), ['png', 'jpg', 'gif']) ) {
    $name = 'folio/albums/' . $folder . '/' . time() . '.' . $ext;
    move_uploaded_file($f['tmp_name'], $name);
    echo  'http://'.$_SERVER['HTTP_HOST'] . '/screenshots/' . $name;
} else {
    echo "Unsupported file format";
}

function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}
