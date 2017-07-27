#!/usr/bin/python

import logging
import os

	
LOG_PATH = os.path.join('/var','log','sentry','sentry.log')

DB_ROOT            = os.path.join('/var','local','sqlite','db')
SENSORDATA_DB_PATH = os.path.join(DB_ROOT, 'sensordata.db')
REGISTER_DB_PATH   = os.path.join(DB_ROOT, 'register.db')

TEMP_C = 'temp_c'
