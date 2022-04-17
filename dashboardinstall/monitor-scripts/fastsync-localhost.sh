#!/bin/bash
service=$(cat /var/dashboard/services/fastsync_localhost | tr -d '\n')

if [[ $service == 'start' ]]; then
  echo 'running' > /var/dashboard/services/fastsync_localhost
  #snap_height=$(wget -q https://helium-snapshots.nebra.com/latest.json -O - | grep -Po '\"height\": [0-9]*' | sed 's/\"height\": //')
  #wget https://helium-snapshots.nebra.com/snap-$snap_height -O /home/pi/hnt/miner/snap/snap-latest
  #获取堡垒机里的地址，去堡垒机里面拉取数据
  url=$(cat /var/dashboard/statuses/fastsync_localhost | tr -d '\n')
  wget $url -O /home/pi/hnt/miner/snap/snap-latest
  docker exec miner miner repair sync_pause
  docker exec miner miner repair sync_cancel
  docker exec miner miner snapshot load /var/data/snap/snap-latest
fi

if [[ $service == 'running' ]]; then
  sync_state=$(docker exec miner miner repair sync_state)
  if [[ $sync_state == 'sync active' ]]; then
    docker exec miner miner repair sync_resume
    echo 'stopped' > /var/dashboard/services/fastsync_localhost
  fi
fi
