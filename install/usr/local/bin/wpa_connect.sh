#/bin/bash

ESSID=$1
PSK=$2

## STOP HOSTAPD
systemctl stop hostapd

## RECONFIGURE HOSTAPD CHANNEL TO USE ESSID CHANNEL

channel=$(iw-chan.sh $ESSID)
if [ $channel -ne 0 ]
then
    sed -i  '/channel=/ s/=.*/='"$channel"'/' /etc/hostapd/hostapd.conf
fi


## RESET WPA_SUPPLICANT CONFIG FILE
CONF=/etc/wpa_supplicant/wpa_supplicant.conf
BLANK=/etc/wpa_supplicant/wpa_supplicant.conf.blank

if [ -e $CONF ]
then
    echo "wpa_supplicant.conf found.."
    rm $CONF
    echo "wpa_supplicant.conf removed.."
fi

cp $BLANK $CONF

echo "wpa_supplicant.conf default created.."

## CONFIGURE WPA_SUPPLICANT CONFIG FILE

echo "network={\r\n\tssid=\""$ESSID"\"\r\n\tpsk=\""$PSK"\"\r\n}\r\n" >> $CONF

wpa_cli reconfigure

sleep 10

## START HOSTAPD
systemctl start hostapd

exit 0


