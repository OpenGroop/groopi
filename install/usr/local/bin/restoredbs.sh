#!/bin/sh

## COPY THE LAST RESTORE FILE 
## FROM /media/usb0/ TO /tmp/
cd /media/usb0
ARCHIVE=`ls | grep tar.gz | tail -1`
cp $ARCHIVE /tmp/$ARCHIVE

## UNTAR THE RESTORE FILE AND
## COPY TO DATABASE DIRECTORY
## BACKUP OVERWRITTEN FILES
cd /tmp/
tar -xzf $ARCHIVE 
cp -rfb data /srv/sqlite3/

## SET PERMISIONS TO RESTORED FILES
cd /srv/sqlite3/
chown -R www-data:www-data /srv/sqlite3/data
chmod 775 /srv/sqlite3/data
chmod 664 /srv/sqlite3/data/*

## CLEAN UP /tmp/
cd /tmp/
rm -rf data $ARCHIVE

exit 0

