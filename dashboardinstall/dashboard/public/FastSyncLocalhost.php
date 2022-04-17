<?php

$file_path = trim(file_get_contents("/var/dashboard/statuses/fastsunc_localhost/var/dashboard/statuses/fastsunc_localhost"));
if ($file_path != '')
{
    $file = fopen('/var/dashboard/services/fastsync_localhost', 'w');
    fwrite($file, "start\n");
    fclose($file);
} else {
    echo 'Error, please set file path.';
}
?>