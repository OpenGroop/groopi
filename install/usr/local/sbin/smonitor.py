#!/usr/bin/python

import logging
import os
import pyudev
import subprocess
import sys
import sconnect
import time

TTY = 'tty'
LOG_PATH = os.path.join('/var','log','sentry','sentry.log')
logging.basicConfig(filename=LOG_PATH,level=logging.DEBUG, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s' )

def establishConnections(devname):
	sc = sconnect.SConnect(devname)
	sc.daemon = True
	sc.start()


context = pyudev.Context()
monitor = pyudev.Monitor.from_netlink(context)
monitor.filter_by(TTY)

# CHECKS IF DEVICE IS ALREADY PLUGGED IN
for device in context.list_devices(subsystem='tty'):
	if device['DEVNAME'].find('ttyACM') > -1:
		devname = device['DEVNAME']
		logging.debug('ACM device detected')
		logging.debug('ACM device: %s', devname)
		establishConnections(devname)
		time.sleep(2)

# LISTENS FOR DEVICE TO BE PLUGGED IN
try:
	for device in iter(monitor.poll, None):
#		print device.items()
		if device['DEVNAME'].find('ttyACM') >= 0:
			action  = device.action
			devname = device['DEVNAME']
			logging.info(' Device %s detected', action)
			logging.debug('Device %s ID_VENDOR_ID: %s', action, device['ID_VENDOR_ID'])
			logging.debug('Device %s DEVNAME: %s', action, devname)
			if action == 'add': 
				establishConnections(devname)

except KeyboardInterrupt:
	sys.exit(0)
