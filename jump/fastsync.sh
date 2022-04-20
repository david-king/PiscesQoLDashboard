#!/bin/bash

snap_height=$(wget -q https://helium-snapshots.nebra.com/latest.json -O - | grep -Po '"height": [0-9]*' | sed 's/"height": //')
wget --no-check-certificate https://helium-snapshots.nebra.com/snap-$snap_height -O /www/miner/snap/snap-latest
