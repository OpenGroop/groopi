#!/bin/sh

DATE=`date +%s`
EXT=".tar.gz"
FILENAME=$DATE$EXT

cd /srv/sqlite3/

tar -czf /media/usb/$FILENAME data

exit 0
