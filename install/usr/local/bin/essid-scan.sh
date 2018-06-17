#!/bin/bash

iwlist wlan0 scanning | grep ESSID | cut -d ':' -f2 | sed 's/"//g'

exit 0

