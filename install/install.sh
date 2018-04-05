#!/bin/bash
#########
##

echo "Setting hwclock ..."
echo date
hwclock -w
hwclock -r

## USERSPACE
#############

# DELETE pi USER
deluser --remove-home pi

# ADD sentry USER 
adduser --no-create-home --disabled-password --disabled-login --gecos "" sentry

# SET USERS TO GROUPS
usermod -aG dialout sentry
usermod -aG www-data sentry
echo 'www-data    ALL=(ALL) NOPASSWD: /usr/local/bin/wpa_conf.py, \
                                      /usr/local/bin/wpa_disconnect.sh, \
                                      /usr/local/bin/backupdbs.sh, \
                                      /usr/local/bin/restoredbs.sh, \
                                      /usr/local/bin/iw-chan.sh, \
                                      /usr/local/bin/hostapd-stop.sh, \
                                      /usr/local/bin/hostapd-start.sh, \
                                      /usr/local/bin/hostapd-reconf.sh, \
                                      /usr/local/bin/hwclock-set.sh \ 
                                      '| EDITOR='tee -a' visudo


## PACKAGES
############

# APT-GET PACKAGES
apt-get update
apt-get -y upgrade
apt-get install -y `cat packages.install`

systemctl stop hostapd
systemctl stop dnsmasq

# PIP PACKAGES
pip install pyudev pyserial

# SENTRY PACKAGE
echo "Getting sentry files..."
cp -R -v etc/* /etc/
cp -R -v lib/* /lib/
cp -R -v usr/* /usr/
echo "Copying var/www/* -> /var/www/..."
cp -R var/www/* /var/www/

## ENVIRONMENTS
####################################

# SETUP LIGHTTPD ENVIRONMENT
echo "Setting up lighttpd..."

echo "Configuring lighttpd.conf..."
chown -v root:root /etc/lighttpd/lighttpd.conf
chmod -v 644 /etc/lighttpd/lighttpd.conf


echo "Configuring SSL..."
mkdir -v /etc/lighttpd/ssl
openssl req -x509 -newkey rsa:4096 -keyout /etc/lighttpd/ssl/keycert.pem -out /etc/lighttpd/ssl/keycert.pem -days 365 -nodes -subj '/CN=groopi'
chown -Rv root:root /etc/lighttpd/ssl/
chmod -Rv 600 /etc/lighttpd/ssl/
lighty-enable-mod ssl
sed -i 's:server.pem:ssl/keycert.pem:' /etc/lighttpd/conf-enabled/10-ssl.conf


echo "Enabling fast-cgi..."
lighty-enable-mod fastcgi
lighty-enable-mod fastcgi-php

echo "Removing default direcory.."
rm -rf /var/www/html

echo "Reloading lighttpd..."
service lighttpd force-reload

# SETUP SQLITE3 ENVIRONMENT
echo "Setting up sqlite3 databases..."
mkdir -pv /srv/sqlite3/data
chmod 775 /srv/sqlite3/data
echo "Setting up register.db..."
sqlite3 /srv/sqlite3/data/register.db <<EOS
	CREATE TABLE device_registers (id INTEGER PRIMARY KEY AUTOINCREMENT, device_id TEXT, device_alias TEXT, granular_table TEXT, summary_table TEXT, valid INTEGER, uom);
EOS

echo "Setting up system.db..."
sqlite3 /srv/sqlite3/data/system.db <<EOS
	CREATE TABLE usb(valid INTEGER);
	INSERT INTO usb(valid) VALUES(0);
EOS

echo "Setting up user.db..."
sqlite3 /srv/sqlite3/data/user.db <<EOS
	CREATE TABLE hash(id INTEGER PRIMARY KEY AUTOINCREMENT, value TEXT, user TEXT);
EOS

touch /srv/sqlite3/data/sensordata.db

chown -Rv www-data:www-data /srv/sqlite3
chmod -v 664 /srv/sqlite3/data/*
php adduser.php

# SETUP SENTRY ENVIRONMENT
echo "Setting up sentry files..."

echo "Setting up service files..."
ln -sv /lib/systemd/system/sdeviced.service /etc/systemd/system/multi-user.target.wants/
ln -sv /lib/systemd/system/susbd.service /etc/systemd/system/multi-user.target.wants/

systemctl daemon-reload

echo "Setting up /usr/local/sbin files..."
chown -v root:root /usr/local/sbin/*
chmod -v 755 -R /usr/local/sbin/*

echo "Setting up /usr/local/bin files..."
chown -v root:www-data /usr/local/bin/*
chmod -v 550 /usr/local/bin/*

echo "Setting up log files..."
mkdir -v /var/log/sentry
chown -v sentry:sentry /var/log/sentry
chmod -v 644 /etc/logrotate.d/sentry

echo "Setting up crontab..."
crontab -l -u sentry; echo "57 23 * * * /usr/local/sbin/saggregator.py" | crontab -u sentry -

echo "Reloading daemons..."
systemctl daemon-reload

echo "Starting sdeviced..."
service sdeviced start

# SETUP PUBLIC ENVIRONMENT
echo "Setting up php5..."
echo "Configuring php5/cgi/php.ini..."
sed -i 's/session.use_strict_mode = 0/session.use_strict_mode = 1/' /etc/php5/cgi/php.ini
echo "Setting ownership for public files....."
chown -R root:www-data /var/www/public
echo "Setting permissions for public files....."
chmod -R 755 /var/www/public
echo "Create softlink to jpgraph-4.0.2....."
ln -sv /var/www/lib/jpgraph-4.0.2 /var/www/lib/jpgraph

# SETUP NETWORK ENVIRONMENT
echo "Setting up network..."
echo "Setting wpa_supplicant log-level to warning..."
wpa_cli log_level warning

echo "Setting up /etc/wpa_supplicant/wpa_supplicant.conf..."
chown -v root:www-data /etc/wpa_supplicant/wpa_supplicant.conf*
chmod -v 660 /etc/wpa_supplicant/wpa_supplicant.conf*

echo "Appending rules to ipatables..."
iptables -N _uap0
iptables -v -A _uap0 -i uap0 -p udp --dport 67:68 -j ACCEPT
iptables -v -A _uap0 -o uap0 -p udp --sport 67:68 -m state --state ESTABLISHED -j ACEEPT
iptables -v -A _uap0 -m limit --limit 2/min -j LOG --log-prefix 'IPT-UAP0: '
iptables -v -A INPUT -p tcp -m tcp --dport 80  -j ACCEPT
iptables -v -A INPUT -p tcp -m tcp --dport 443 -j ACCEPT
iptables -v -A INPUT -i uap0 -j _uap0
iptables -v -A OUTPUT -p tcp -m tcp --sport 80   -m state --state ESTABLISHED -j ACCEPT
iptables -v -A OUTPUT -p tcp -m tcp --sport 443  -m state --state ESTABLISHED -j ACCEPT
iptables -v -A OUTPUT -o uap0 -j _uap0


# SETTING UP /etc/dhcpcd.conf
echo "Setting up /etc/dhcpcd.conf"
echo 'denyinterfaces uap0' >> /etc/dhcpcd.conf

# SETUP HOSTAPD
echo "Setting up hostapd..."
echo "Configuring hostapd.conf..."

prefix="iq-"
line=$(ifconfig wlan0 | grep HWaddr )
addr=${line:((${#line}-7)):5}
essid=$prefix${addr/:/}
echo 'ssid='$essid >> /etc/hostapd/hostapd.conf

passphrase=$(head /dev/urandom | tr -dc a-z0-9 | head -c 8)
echo 'wpa_passphrase='$passphrase >> /etc/hostapd/hostapd.conf

echo 'DAEMON_CONF="/etc/hostapd/hostapd.conf"' >> /etc/default/hostapd
update-rc.d hostapd defaults

echo "Reconfiguring /etc/apt/sources.list ..."
echo 'deb https://mirrordirector.raspbian.org/raspbian jessie main non-free contrib rpi' > /etc/apt/sources.list

echo "Finished"
echo ""
echo "*************************"
echo ""
echo "ESSID   : "$essid
echo "PASSWORD: "$passphrase

echo ""
echo "*************************" 
echo ""
echo "Shutting system down ...."

poweroff

exit 0
