#!/usr/bin/python

import logging
import os
import pyudev
import subprocess
import sys
import sconnect
import susb
import time
from sconstants import LOG_PATH

TTY     = 'tty'
TTY_ACM = 'ttyACM'

logging.basicConfig(filename=LOG_PATH,level=logging.INFO, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s' )

def establishConnections(devname):
	sc = sconnect.SConnect(devname)
	sc.daemon = True
	sc.start()


context = pyudev.Context()
monitor = pyudev.Monitor.from_netlink(context)
monitor.filter_by(TTY)

## CHECKS IF DEVICE IS ALREADY PLUGGED IN
for device in context.list_devices(subsystem=TTY):
	if device['DEVNAME'].find(TTY_ACM) > -1:
		devname = device['DEVNAME']
		logging.info('ACM device detected')
		logging.info('ACM device: %s', devname)
		establishConnections(devname)
		time.sleep(2)


## LISTENS FOR DEVICE TO BE PLUGGED IN
try:
	for device in iter(monitor.poll, None):
#		print device.items()
		if device['DEVNAME'].find(TTY_ACM) >= 0:
			action  = device.action
			devname = device['DEVNAME']
			logging.info(' Device %s detected', action)
			logging.info(' %s %s', action, devname)
			if action == 'add': 
				establishConnections(devname)
				
except KeyboardInterrupt:
	sys.exit(0)
