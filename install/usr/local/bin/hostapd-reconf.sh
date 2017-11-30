#!/bin/bash

channel=$(iw-chan.sh $1)

sed -i  '/channel=/ s/=.*/='"$channel"'/' /etc/hostapd/hostapd.conf

exit 0