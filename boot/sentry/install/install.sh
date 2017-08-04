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
usermod -aG staff suser
usermod -aG www-data suser


## PACKAGES
############

# APT-GET PACKAGES
apt-get install -y `cat packages.install`

# PIP PACKAGES
pip install pyudev pyserial

# SENTRY PACKAGE
cp -rv usr/local/sbin /usr/local/
cp -v lib/systemd/system/sdeviced.service /lib/systemd/system/sdeviced.service
cp -v etc/init.d/sdeviced /etc/init.d/sdeviced
cp -v etc/logrotate.d/sentry /etc/logrotate.d/sentry
cp -v var/www/public /var/www/public
## ENVIRONMENTS
####################################


# SETUP LIGHTTPD ENVIRONMENT
mkdir -v /var/www/public
chown -v suser:suser /var/www/public
sed -i 's:/var/www/html:/var/www/public:' /etc/lighttpd/lighttpd.conf
echo "server.error-handler-404 = \"/home.php\"" >> /etc/lighttpd/lighttpd.conf

# SETUP SQLITE3 ENVIRONMENT
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
chmod 664 /srv/sqlite3/data/register.db
chmod 664 /srv/sqlite3/data/sensordata.db
chmod 644 /srv/sqlite3/data/user.db

# SETUP SENTRY ENVIRONMENT
ln /lib/systemd/system/sdeviced.service /etc/systemd/system/multi-user.target.wants/
chmod 775 -R /usr/local/sbin
mkdir /var/log/sentry
chown sentry:sentry /var/log/sentry
chmod 644 /etc/logrotate.d/sentry
(crontab -l -u sentry; echo "57 23 * * * /usr/local/sbin/saggregator.py") | crontab -u sentry -



# SETUP PUBLIC ENVIRONMENT
sed -i 's/session.use_strict_mode = 0/session.use_strict_mode = 1/' /etc/php5/cli/php.ini

iptables -A INPUT -p tcp -m tcp --dport 80  -j ACCEPT
iptables -A INPUT -p tcp -m tcp --dport 443 -j ACCEPT

iptables -A OUTPUT -p tcp -m tcp --sport 80   -m state --state ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp -m tcp --sport 443  -m state --state ESTABLISHED -j ACCEPT

# CREATE SQLITE3 TABLES



# CONFIGURE SENTRY ENVIRONMENT



# apt-get purge -y alsa-utils
# find / -type f -name "*-old" |xargs rm -rf
# rm -rf /var/backups/* /var/lib/apt/lists/* ~/.bash_history
# find /var/log/ -type f |xargs rm -rf
# cp /dev/null /etc/resolv.conf

# poweroff

exit 0
