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
# usermod -aG staff suser
# usermod -aG www-data suser
#

## PACKAGES
############

# APT-GET PACKAGES
apt-get install -y `cat packages.install`

# PIP PACKAGES
pip install pyudev pyserial

# SENTRY PACKAGE
echo "Getting sentry files..."
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
sed -i 's:/var/www/html:/var/www/public:' /etc/lighttpd/lighttpd.conf
echo "server.error-handler-404 = \"/home.php\"" >> /etc/lighttpd/lighttpd.conf
echo "Reloading lighttpd..."
service lighttpd reload

# SETUP SQLITE3 ENVIRONMENT
echo "Setting up sqlite3 databases..."
mkdir -pv /srv/sqlite3/data
chmod 775 /srv/sqlite3/data

sqlite3 /srv/sqlite3/data/register.db <<EOS
	CREATE TABLE device_registers (id INTEGER PRIMARY KEY AUTOINCREMENT, device_id TEXT, device_alias TEXT, granular_table TEXT, summary_table TEXT, valid INTEGER, uom);
EOS

sqlite3 /srv/sqlite3/data/user.db <<EOS
	CREATE TABLE hash(id INTEGER PRIMARY KEY AUTOINCREMENT, value TEXT, user TEXT);
EOS

touch /srv/sqlite3/data/sensordata.db

chown -Rv www-data:www-data /srv/sqlite3/data
chmod -v 664 /srv/sqlite3/data/register.db
chmod -v 664 /srv/sqlite3/data/sensordata.db
chmod -v 644 /srv/sqlite3/data/user.db

# SETUP SENTRY ENVIRONMENT
echo "Setting up sentry..."
ln -v /lib/systemd/system/sdeviced.service /etc/systemd/system/multi-user.target.wants/
chmod -v 775 -R /usr/local/sbin
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
echo "Configuring php5/cli/php.ini..."
sed -i 's/session.use_strict_mode = 0/session.use_strict_mode = 1/' /etc/php5/cli/php.ini
echo "Reloading php5-fpm..."
service php5-fpm reload

echo "Appending rules to ipatables..."
iptables -Av INPUT -p tcp -m tcp --dport 80  -j ACCEPT
iptables -Av INPUT -p tcp -m tcp --dport 443 -j ACCEPT

iptables -Av OUTPUT -p tcp -m tcp --sport 80   -m state --state ESTABLISHED -j ACCEPT
iptables -Av OUTPUT -p tcp -m tcp --sport 443  -m state --state ESTABLISHED -j ACCEPT
echo "Finished!"
exit 0
