#!/bin/sh

read -p "Enter hostname: " HOSTNAME
read -p "Enter username: " USERNAME
read -p "Enter SSH port: " SSH_PORT
# ADD NEW USER 
echo "Creating new user....."
adduser --gecos "" $USERNAME
usermod -aG sudo $USERNAME
echo "New user created....."

# TZDATA
echo "Reconfiguring timezone....."
dpkg-reconfigure tzdata
echo "Timezone reconfigured....."

# IPTABLES
echo "Configuring iptables....."
iptables -v -A INPUT -i lo -j ACCEPT
iptables -v -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -v -A INPUT -p tcp --dport $SSH_PORT -j ACCEPT
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

cp -v etc/network/if-pre-up.d/ipt-restore /etc/network/if-pre-up.d/ipt-restore
cp -v etc/network/if-post-down.d/ipt-save /etc/network/if-post-down.d/ipt-save

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
sed -i 's/^\(Port \).*/\1'"$SSH_PORT"'/' /etc/ssh/sshd_config
sed -i 's/^\(PermitRootLogin \).*/\1no/' /etc/ssh/sshd_config
echo "sshd_config configured....."

# HOSTNAME
echo "Changing hostname....."
sed -i 's/raspberrypi/'"$HOSTNAME"'/' /etc/hostname
sed -i 's/raspberrypi/'"$HOSTNAME"'/' /etc/hosts
echo "Hostname changed....."

# # EXPAND FILESYSTEM
# # Get the starting offset of the root partition
# PART_START=$(parted /dev/mmcblk0 -ms unit s p | grep "^2" | cut -f 2 -d:)
# [ "$PART_START" ] || return 1
# # Return value will likely be error for fdisk as it fails to reload the 
# # partition table because the root fs is mounted
# fdisk /dev/mmcblk0 <<EOF
# p
# d
# 2
# n
# p
# 2
# $PART_START

# p
# w
# EOF
# # ASK_TO_REBOOT=1

# # now set up an init.d script
# cat <<\EOF > /etc/init.d/resize2fs_once &&
# #!/bin/sh
# ### BEGIN INIT INFO
# # Provides:          resize2fs_once
# # Required-Start:
# # Required-Stop:
# # Default-Start: 2 3 4 5 S
# # Default-Stop:
# # Short-Description: Resize the root filesystem to fill partition
# # Description:
# ### END INIT INFO

# . /lib/lsb/init-functions

# case "$1" in
#   start)
#     log_daemon_msg "Starting resize2fs_once" &&
#     resize2fs /dev/mmcblk0p2 &&
#     rm /etc/init.d/resize2fs_once &&
#     update-rc.d resize2fs_once remove &&
#     log_end_msg $?
#     ;;
#   *)
#     echo "Usage: $0 start" >&2
#     exit 3
#     ;;
# esac
# EOF
# chmod +x /etc/init.d/resize2fs_once &&
# update-rc.d resize2fs_once defaults &&

echo "Rebooting machine....."
echo "Login at $USERNAME@$HOSTNAME -p $SSH_PORT"

reboot

exit 0

