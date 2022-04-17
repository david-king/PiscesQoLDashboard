<?php

if (isset($_POST['file_url']) && $_POST['file_url'] != '') {
    $config = $_POST['file_url'];
    $file = fopen("/var/dashboard/statuses/fastsync_localhost", "w");
    fwrite($file, $config);
    fclose($file);
    echo 'ok';
} else {
    echo 'Error, please try again.';
}