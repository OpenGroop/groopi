#!/bin/bash

prefix="iq-"

line=$(ifconfig wlan0 | grep HWaddr )

addr=${line:((${#line}-7)):5}

essid=$prefix${addr/:/}

echo $essid

exit 0