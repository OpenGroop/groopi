#!/usr/bin/python

import logging
import pyudev
import sys
import susb
import time
from sconstants import LOG_PATH

BLOCK = 'block'
SDA1  = 'sda1'

logging.basicConfig(filename=LOG_PATH,level=logging.info, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s' )




context = pyudev.Context()
monitor = pyudev.Monitor.from_netlink(context)
monitor.filter_by(BLOCK)

## CHECKS IF DEVICE IS ALREADY PLUGGED IN
for device in context.list_devices(subsystem=BLOCK):
	if device['DEVNAME'].find(SDA1) >= 0:
		devname = device['DEVNAME']
		logging.info('Device %s detected', devname)
		log_msg = ''
		susb.SUSB.validate()
		time.sleep(2)


## LISTENS FOR DEVICE TO BE PLUGGED IN
try:
	for device in iter(monitor.poll, None):
#		print device.items()
		if device['DEVNAME'].find(SDA1) >= 0:
			action  = device.action
			devname = device['DEVNAME']
			logging.info(' Device %s detected', action)
			logging.info(' %s %s', action, devname)
			if action == 'add':
				susb.SUSB.validate()
			if action == 'remove':
				susb.SUSB.invalidate()
				
except KeyboardInterrupt:
	sys.exit(0)
