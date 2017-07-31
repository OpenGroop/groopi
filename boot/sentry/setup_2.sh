#!/bin/sh

deluser --remove-home pi
adduser --no-create-home --disabled-password --disabled-login --gecos "" sentry
usermod -aG dialout sentry
usermod -aG www-data sentry

apt-get install -y `cat packages.install`
mkdir -v /var/local/sqlite


# sudo iptables -A INPUT -p tcp -m tcp --dport 80  -j ACCEPT
# sudo iptables -A INPUT -p tcp -m tcp --dport 443 -j ACCEPT

# sudo iptables -A OUTPUT -p tcp -m tcp --sport 80   -m state --state ESTABLISHED -j ACCEPT
# sudo iptables -A OUTPUT -p tcp -m tcp --sport 443  -m state --state ESTABLISHED -j ACCEPT





apt-get purge -y alsa-utils
find / -type f -name "*-old" |xargs rm -rf
rm -rf /var/backups/* /var/lib/apt/lists/* ~/.bash_history
find /var/log/ -type f |xargs rm -rf
cp /dev/null /etc/resolv.conf

poweroff

exit 0
