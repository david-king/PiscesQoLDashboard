#!/bin/bash

#exec 3>&1 4>&2 1>> jump.log 2>&1
read -n1 -p "whether to replace yum to aliyun [y/n]：" yum_aliyun
case $yum_aliyun in
  Y | y)
    echo 'set yum aliyun.'
    cp /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup
    curl -o /etc/yum.repos.d/CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-7.repo
    yum clean all && yum makecache && yum -y update;;
  N | n)
    echo 'not replace yum to aliyun';;
esac

if ! type nginx >/dev/null 2>&1; then
  echo 'start install nginx...'
  mkdir nginx && cd nginx || exit
  yum install -y wget gcc pcre-devel zlib-devel openssl-devel
  nginx_version='nginx-1.18.0'
  wget http://nginx.org/download/$nginx_version.tar.gz
  if [ $? -eq 0 ]; then
    tar -zxvf $nginx_version.tar.gz
    cd $nginx_version || exit
    ./configure --prefix=/usr/local/nginx
    make && make install

    cp -r nginx.conf /usr/local/nginx/conf/nginx.conf

    systemctl stop firewalld.service
    systemctl disable firewalld.service

    ln -s /usr/local/nginx/sbin/nginx /usr/local/sbin/
    ln -s /usr/local/nginx/bin/nginx /usr/local/bin/
    nginx

    echo 'install nginx successful.'
  else
    echo 'wget nginx error.'
  fi
else
  echo 'existing nginx.'
fi

if ! type crond >/dev/null 2>&1; then
  yum install -y cronie crontabs
  echo 'is not crontab, install crontab successful.'
else
  echo 'existing crontab.'
fi

FIND_FILE="/etc/crontab"
FIND_STR="fastsync.sh"
if [ $(grep -c "$FIND_STR" $FIND_FILE) -eq '0' ];then
  sed -i '$a\*/30 \* \* \* \* root (bash /root/jump/fastsync.sh)' /etc/crontab
  mkdir /www/miner/snap
  echo 'set crontab ok.'
else
  echo 'existing crontab fastsync.sh.'
fi
