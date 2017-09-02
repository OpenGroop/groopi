#!/usr/bin/python

import logging
import os

	
LOG_PATH = os.path.join('/var','log','sentry','sentry.log')

DB_ROOT            = os.path.join('/srv','sqlite3','data')
SENSORDATA_DB_PATH = os.path.join(DB_ROOT, 'sensordata.db')
SYSTEM_DB_PATH     = os.path.join(DB_ROOT, 'system.db')
REGISTER_DB_PATH   = os.path.join(DB_ROOT, 'register.db')

TEMP_C = 'temp_c'
