<?php

// 内网IP
$hostIp = trim(file_get_contents("/var/dashboard/statuses/local-ip"));
$info['hostIp'] = $hostIp!=''?trim(explode(':', $hostIp)[1]):'';
// 壁垒机IP
$jumpIp = trim(file_get_contents("/var/dashboard/statuses/fastsync_localhost"));
$info['jumpIp'] = $jumpIp!=''?parse_url($jumpIp)['host']:'';
// 本机高度
$info['minerHeight'] = trim(file_get_contents("/var/dashboard/statuses/infoheight"));
// 主网高度
$info['liveHeight'] = trim(file_get_contents("/var/dashboard/statuses/current_blockheight"));
// 主机名称
$info['hntName'] = ucwords(trim(file_get_contents("/var/dashboard/statuses/animal_name")));
// 硬盘使用百分比
$diskfree = disk_free_space(".") / 1073741824;
$disktotal = disk_total_space(".") / 1073741824;
$diskused = $disktotal - $diskfree;
$info['disk'] = round($diskused/$disktotal*100, 2);
// 主机码
$info['hotspotId'] = trim(file_get_contents("/var/dashboard/statuses/pubkey"));
// 主机状态，1在线，2离线，3异常，4同步，5下架。
$info['onlineStatus'] = 1;
$online = trim(file_get_contents("/var/dashboard/statuses/online_status"));
if ($online == 'online') {
    if (empty($info['liveHeight'])) {
        $info['onlineStatus'] = 3;
    } elseif (($info['liveHeight'] - $info['minerHeight']) <= 20) {
        if ($info['hntName'] != '') {
            $info['onlineStatus'] = 1;
        }else {
            $info['onlineStatus'] = 3;
        }
    } else {
        $info['onlineStatus'] = 4;
    }
} else {
    $info['onlineStatus'] = 3;
}

function post($url, $post_data) {
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

$config = json_decode(trim(file_get_contents("/var/dashboard/statuses/sync_heart")), true);
if ($config['url'] != '')
    post($config['url'], $info);

?>