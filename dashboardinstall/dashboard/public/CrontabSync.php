<?php

$status = strip_tags(htmlentities($_GET['status']));
$info = json_decode(trim(file_get_contents('/var/dashboard/statuses/sync_heart')), true);

try {
    if ((isset($info['url']) && isset($info['crontab'])) && ($info['url'] != '' && $info['crontab'] != '')) {
        switch ($status) {
            // 开
            case 'enabled':
                $file = fopen('/var/dashboard/services/crontabsycn', 'w');
                fwrite($file, "disabled\n");
                fclose($file);

                exec("sudo sed -i '/survival.php/d' /etc/crontab");
                break;
            // 关
            case 'disabled':
                $file = fopen('/var/dashboard/services/crontabsync', 'w');
                fwrite($file, "enabled\n");
                fclose($file);

                exec("sudo sed -i '\$a".$info['crontab']." root (php /var/dashboard/api/survival.php)' /etc/crontab");
                break;
        }
//        shell_exec("service cron restart");
        echo 'ok';
    } else {
        echo 'please setting up Sync Heart Data';

    }
} catch (ErrorException $e) {
    echo $e;
}
?>