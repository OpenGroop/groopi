#!/bin/sh
#########

## USERSPACE
#############

# DELETE pi USER
deluser --remove-home pi

# ADD sentry USER 
adduser --no-create-home --disabled-password --disabled-login --gecos "" sentry

# SET USERS TO GROUPS
usermod -aG dialout sentry
usermod -aG www-data sentry
echo 'www-data    ALL=(ALL) NOPASSWD: /usr/local/bin/wpa_conf.py, /sbin/wpa_cli reconfigure' | EDITOR='tee -a' visudo


## PACKAGES
############

# APT-GET PACKAGES
apt-get install -y `cat packages.install`

# PIP PACKAGES
pip install pyudev pyserial

# SENTRY PACKAGE
echo "Getting sentry files..."
cp -v usr/local/bin/wpa_conf.py /usr/local/bin/wpa_conf.py
cp -rv usr/local/sbin /usr/local/
cp -v lib/systemd/system/sdeviced.service /lib/systemd/system/sdeviced.service
cp -v etc/init.d/sdeviced /etc/init.d/sdeviced
cp -v etc/logrotate.d/sentry /etc/logrotate.d/sentry
echo "Copying var/www/public -> /var/www/public..."
cp -r var/www/public /var/www/

## ENVIRONMENTS
####################################


# SETUP LIGHTTPD ENVIRONMENT
# chown -v suser:suser /var/www/public
echo "Setting up lighttpd..."
echo "Configuring lighttpd.conf..."

cp -v /etc/lighttpd/lighttpd.conf /etc/lighttpd/lighttpd.conf.copy
rm -v /etc/lighttpd/lighttpd.conf
cp -v etc/lighttpd/lighttpd.conf /etc/lighttpd/lighttpd.conf
chown -v root:root /etc/lighttpd/lighttpd.conf
chmod -v 644 /etc/lighttpd/lighttpd.conf

# sed -i 's:/var/www/html:/var/www/public:' /etc/lighttpd/lighttpd.conf
# echo "server.error-handler-404 = \"/home.php\"" >> /etc/lighttpd/lighttpd.conf
# echo "ssl.use-sslv3 = \"disable\"" >> /etc/lighttpd/lighttpd.conf
# echo "\$SERVER[\"socket\"] == \":80\" {" >> /etc/lighttpd/lighttpd.conf
# echo "  \$HTTP[\"host\"] =~ \"(.*)\" {" >> /etc/lighttpd/lighttpd.conf
# echo "    url.redirect = ( \"^/(.*)\" => \"https://%1/\$1\" )" >> /etc/lighttpd/lighttpd.conf
# echo "  }" >> /etc/lighttpd/lighttpd.conf
# echo "}" >> /etc/lighttpd/lighttpd.conf


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

echo "Setting up user.db..."
sqlite3 /srv/sqlite3/data/user.db <<EOS
	CREATE TABLE hash(id INTEGER PRIMARY KEY AUTOINCREMENT, value TEXT, user TEXT);
EOS

touch /srv/sqlite3/data/sensordata.db

chown -Rv www-data:www-data /srv/sqlite3/data
chmod -v 664 /srv/sqlite3/data/register.db
chmod -v 664 /srv/sqlite3/data/sensordata.db
chmod -v 644 /srv/sqlite3/data/user.db

# SETUP SENTRY ENVIRONMENT
echo "Setting up sentry files..."
echo "Setting up service file..."
ln -v /lib/systemd/system/sdeviced.service /etc/systemd/system/multi-user.target.wants/
echo "Setting up daemon files..."
chmod -v 775 -R /usr/local/sbin
echo "Setting up log files..."
mkdir -v /var/log/sentry
chown -v sentry:sentry /var/log/sentry
chmod -v 644 /etc/logrotate.d/sentry
echo "Setting up crontab..."
(crontab -l -u sentry; echo "57 23 * * * /usr/local/sbin/saggregator.py") | crontab -u sentry -
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
ln -sv /var/www/public/lib/jpgraph-4.0.2 /var/www/public/lib/jpgraph

# SETUP NETWORK ENVIRONMENT
echo "Setting up network..."
echo "Setting up /usr/local/bin/wpa_conf.py..."
chown -v root:www-data /usr/local/bin/wpa_conf.py
chmod -v 550 /usr/local/bin/wpa_conf.py
echo "Setting up /etc/wpa_supplicant/wpa_supplicant.conf..."
chown -v root:www-data /etc/wpa_supplicant/wpa_supplicant.conf
chmod -v 660 /etc/wpa_supplicant/wpa_supplicant.conf
echo "Appending rules to ipatables..."
iptables -v -A INPUT -p tcp -m tcp --dport 80  -j ACCEPT
iptables -v -A INPUT -p tcp -m tcp --dport 443 -j ACCEPT
iptables -v -A OUTPUT -p tcp -m tcp --sport 80   -m state --state ESTABLISHED -j ACCEPT
iptables -v -A OUTPUT -p tcp -m tcp --sport 443  -m state --state ESTABLISHED -j ACCEPT

echo "Finished"
echo "Rebooting system...."

reboot

exit 0
