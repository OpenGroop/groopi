#!/bin/sh


# IPTABLES
echo "Configuring iptables....."
iptables -v -A INPUT -i lo -j ACCEPT
iptables -v -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -v -A INPUT -p tcp --dport 22 -j ACCEPT
iptables -v -A INPUT -p tcp --dport 2112 -j ACCEPT
iptables -v -A OUTPUT -o lo -j ACCEPT
iptables -v -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -v -A OUTPUT -p tcp --dport 80  -j ACCEPT
iptables -v -A OUTPUT -p tcp --dport 443 -j ACCEPT
iptables -v -A OUTPUT -p udp --dport 53  -j ACCEPT
iptables -v -A OUTPUT -p udp --dport 67:68 -j ACCEPT
iptables -v -A OUTPUT -p udp --dport 123 -j ACCEPT
	
iptables -v -P INPUT DROP
iptables -v -P FORWARD DROP
iptables -v -P OUTPUT DROP

ip6tables -v -P INPUT DROP
ip6tables -v -P FORWARD DROP
ip6tables -v -P OUTPUT DROP

mkdir -v /etc/iptables

cp -v /boot/sentry/ipt-restore /etc/network/if-pre-up.d/ipt-restore
cp -v /boot/sentry/ipt-save /etc/network/if-post-down.d/ipt-save

chmod +x /etc/network/if-pre-up.d/ipt-restore
chmod +x /etc/network/if-post-down.d/ipt-save

echo "iptables configured....."

# UPDATE APT
echo "Updating apt....."
apt-get update
echo "apt updated....."

# PURGE PACKAGES
echo "Purging uneeded packages....."
apt-get purge -y `cat packages.purge`
apt-get autoremove -y
echo "Uneeded packages purged....."

# UPGRADE PACKAGES
echo "Upgrading packages....."
apt-get upgrade -y
echo "Pakages upgraded....."

echo "Cleaning apt cache....."
apt-get clean
echo "apt cache cleaned....."

# REMOVE DIRECORIES
echo "Removing uneeded directories....."
rm -rfv /opt/vc/src/hello_pi
echo "Uneeded directories removed....."

# REGENERATE SSH KEYS
echo "Regenerating ssh keys....."
rm -v /etc/ssh/ssh_host_*
dpkg-reconfigure openssh-server
/etc/init.d/ssh restart
echo "SSH keys regenerated....."

# CONFIGURE sshd_config
echo "Configuring sshd_config....."
sed -i 's/^\(Port \).*/\12112/' /etc/ssh/sshd_config
sed -i 's/^\(PermitRootLogin \).*/\1no/' /etc/ssh/sshd_config
echo "sshd_config configured....."

# ADD NEW USER 
echo "Creating new user....."
adduser --gecos "" suser
usermod -aG sudo suser
echo "New user created....."

# HOSTNAME
echo "Changing hostname....."
sed -i 's/raspberrypi/sentry/' /etc/hostname
sed -i 's/raspberrypi/sentry/' /etc/hosts
echo "Hostname changed....."

echo "Rebooting machine....."
echo "Login at suser@sentry -p 2112"

reboot

exit 0

