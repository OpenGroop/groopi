#!/bin/sh

systemctl stop hostapd

rm /etc/wpa_supplicant/wpa_supplicant.conf

cp /etc/wpa_supplicant/wpa_supplicant.conf.blank /etc/wpa_supplicant/wpa_supplicant.conf

wpa_cli reconfigure

sleep 10

systemctl start hostapd

exit 0
