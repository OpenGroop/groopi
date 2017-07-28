#!/bin/bash

## ADD NEW USER 
# echo "Creating new user...########################################"
# sudo adduser --disabled-password --gecos "" suser
# echo "suser:sE)@YRj:XNG}Y}dr&^a]MISz7-YEpKYD}mFm{" | sudo chpasswd
# ech0 "New user created..."

## REGENERATE SSH KEYS
# echo "Regenerating ssh keys...####################################"
# sudo rm /etc/ssh/ssh_host_*
# sudo dpkg-reconfigure openssh-server

## PACKAGE MANAGEMENT

dpkg -l | grep -qw lighttpd       || echo "lighttpd"       >> packages-install.tmp
dpkg -l | grep -qw php5           || echo "php5"           >> packages-install.tmp
dpkg -l | grep -qw sqlite3        || echo "sqlite3"        >> packages-install.tmp

# UPDATE APT
sudo apt-get update

# PURGE PACKAGES
xargs -a <(awk '! /^ *(#|$)/' "/boot/packages.purge") -r -- sudo apt-get purge -y
sudo apt-get autoremove -y

# UPGRADE PACKAGES
sudo apt-get upgrade -y

# INSTALL LOCAL PACKAGES
xargs -a <(awk '! /^ *(#|$)/' "packages-install.tmp") -r -- sudo apt-get install --no-install-recommends -y