#!/bin/sh


deluser --remove-home pi
adduser --no-create-home --disabled-password --disabled-login --gecos "" sentry
usermod -aG dialout sentry
usermod -aG www-data sentry

usermod -aG staff suser
usermod -aG www-data suser


apt-get install -y `cat packages.install`

pip install pyudev

mkdir -pv /srv/sqlite3/data
mkdir -v /var/www/public

chown -v www-data:www-data /srv/sqlite3
chown -v www-data:www-data /srv/sqlite3/data
chown -v suser:suser /var/www/public

cp -v /etc/lighttpd/lighttpd.conf /etc/lighttpd/lighttpd.conf.copy
cp -v etc/lighttpd/lighttpd.conf /etc/lighttpd/lighttpd.conf 

cp -v local/sbin/
# sed -i 's:/var/www/html:/var/www/public:' /etc/lighttpd/lighttpd.conf
# echo "server.error-handler-404 = \"/home.php\"" >> /etc/lighttpd/lighttpd.conf

iptables -A INPUT -p tcp -m tcp --dport 80  -j ACCEPT
iptables -A INPUT -p tcp -m tcp --dport 443 -j ACCEPT

iptables -A OUTPUT -p tcp -m tcp --sport 80   -m state --state ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp -m tcp --sport 443  -m state --state ESTABLISHED -j ACCEPT






apt-get purge -y alsa-utils
find / -type f -name "*-old" |xargs rm -rf
rm -rf /var/backups/* /var/lib/apt/lists/* ~/.bash_history
find /var/log/ -type f |xargs rm -rf
cp /dev/null /etc/resolv.conf

poweroff

exit 0
