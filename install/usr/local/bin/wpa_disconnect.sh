#!/bin/sh

rm /etc/wpa_supplicant/wpa_supplicant.conf

cp /etc/wpa_supplicant/wpa_supplicant.conf.blank /etc/wpa_supplicant/wpa_supplicant.conf

wpa_cli reconfigure

exit 0