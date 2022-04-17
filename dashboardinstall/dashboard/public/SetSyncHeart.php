<?php

if ((isset($_POST['url']) && isset($_POST['crontab'])) && ($_POST['url'] != '' && $_POST['crontab'] != "")) {
    $config = json_encode([
        'url' => $_POST['url'],
        'crontab' => $_POST['crontab']
    ], JSON_UNESCAPED_SLASHES);
    $file = fopen("/var/dashboard/statuses/sync_heart", "w");
    fwrite($file, $config);
    fclose($file);
    echo 'ok';
} else {
    echo 'Error, please try again.';
}

