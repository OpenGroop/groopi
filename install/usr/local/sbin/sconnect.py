#!/usr/bin/python

import ast
import exceptions
import logging
import os
import serial
import sqlite3
import sys
import threading

from sconstants import LOG_PATH
from sconstants import REGISTER_DB_PATH
from sconstants import SENSORDATA_DB_PATH
from sconstants import TEMP_C
from smqtt      import SMQTT

#LOG_PATH = os.path.join('/var','log','sentry','sentry.log')
logging.basicConfig(filename=LOG_PATH,level=logging.INFO, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s')

#DB_ROOT            = os.path.join('/var','local','sqlite','db')
#SENSORDATA_DB_PATH = os.path.join(DB_ROOT, 'sensordata.db')
#REGISTER_DB_PATH   = os.path.join(DB_ROOT, 'register.db')

class SConnect(threading.Thread):
    device = ''

    def __init__(self,device):
        threading.Thread.__init__(self)
        self.device = device

    def run(self):

        def getSerialConnection():
            sc = serial.Serial(self.device)
            return sc

        def getDictionary(line):
            d = dict(ast.literal_eval(line))
            return d
        
        def getSummaryTable(deviceID):
            s = deviceID + '_summary' 
            return s

        def getGranularTable(deviceID):
            s = deviceID + '_granular'
            return s

        def registerDevice(deviceID):
            logging.info(' %s: Begin device registration', self.device)
            logging.info(' %s: device=%s', self.device, deviceID)

            ## GET TABLES
            granular_table = getGranularTable(deviceID)
            summary_table  = getSummaryTable(deviceID)

            ## QUERY DEVICE NAME AGAINST REGISTER
            logging.info(' %s/%s: Checking device against register', self.device, deviceID)
            register_db = sqlite3.connect(REGISTER_DB_PATH)
            sql = "SELECT id FROM device_registers WHERE device_id =?"
            cursor = register_db.cursor()
            cursor.execute(sql,(deviceID,))

            ## CREATE DEVICE TABLE IF IT DOES NOT EXIST
            if (len(cursor.fetchall()) == 0):
                ## REGISTER DEVICE
                logging.info(' %s/%s: Device is not registered...proceeding registration process', self.device, deviceID)
                sql = "INSERT INTO device_registers (device_id, device_alias, granular_table, summary_table, valid, uom) VALUES (?,?,?,?,1,?)"
                cursor.execute(sql,(deviceID, deviceID, granular_table, summary_table, TEMP_C))
                register_db.commit()
                cursor.close()
                register_db.close()
                logging.info(' %s/%s: Device validated.', self.device, deviceID)

                ## CONNECT TO SENSORDATA.DB
                sensordata_db = sqlite3.connect(SENSORDATA_DB_PATH)

                ## CREATE STHP GRANULAR TABLE
                logging.info('%s/%s: Creating granular table', self.device, deviceID)
                sql = "CREATE TABLE IF NOT EXISTS {} (id INTEGER PRIMARY KEY AUTOINCREMENT, timestamp INTEGER, ldr TEXT, revision TEXT, device_id TEXT, temp_f TEXT, temp_c TEXT, humidity TEXT)"
                sql = sql.format(granular_table)
                sensordata_db.execute(sql)
                logging.info(' %s/%s: Granular table created', self.device, deviceID)

                ## CREATE STHP SUMMARY TABLE
                logging.info('%s/%s: Creating summary table', self.device, deviceID)
                sql = "CREATE TABLE IF NOT EXISTS {} (id INTEGER PRIMARY KEY AUTOINCREMENT, timestamp INTEGER, tappv NUMERIC, tadpv NUMERIC, taov NUMERIC, tmaxv NUMERIC, tminv NUMERIC, happv NUMERIC, hadpv NUMERIC, haov NUMERIC, hmaxv NUMERIC, hminv NUMERIC)"
                sql = sql.format(summary_table)
                sensordata_db.execute(sql)
                logging.info(' %s/%s: Summary table created', self.device, deviceID)

                ## CLOSE SENSORDATA.DB CONNECTION
                sensordata_db.close()
            else:
                logging.info(' %s/%s: Device is registered...proceeding to validation', self.device, deviceID)
                sql = "UPDATE device_registers SET valid=1 WHERE device_id=?"
                cursor.execute(sql,(deviceID,))
                register_db.commit()
                cursor.close()
                register_db.close()
                logging.info(' %s/%s: Device validated', self.device, deviceID)
            logging.info(' %s/%s: Registration complete', self.device, deviceID)

            return True

        def write(data):
            sql = "INSERT INTO " + getGranularTable(data['device_id'])
            sql = sql + " (timestamp, device_id, revision, ldr, temp_c, temp_f, humidity) "
            sql = sql + "VALUES (strftime('%s','now'), ?, ?, ?, ?, ?, ?)"
    
            try:
                db_conn = sqlite3.connect(SENSORDATA_DB_PATH)   
            except sqlite3.OperationalError, e:
                logging.info(' %s/$s: OperationalError: %s', self.device, deviceID, e)

            try:
                db_conn.execute(sql,(data['device_id'], data['revision'], data['ldr'], data['temp_c'], data['temp_f'], data['humidity']))
                db_conn.commit()
            except sqlite3.OperationalError, e:
                logging.info(' %s/%s: OperationalError: %s', self.device, deviceID, e)
                logging.info(' %s/%s: OperationalError: Closing database connection.', self.device, deviceID)

            db_conn.close()

            SMQTT.push(data)

            return True

        
        ## GO

        sd = getSerialConnection()
        logging.info(' %s: Serial connection established', self.device)
        logging.debug('%s: thread=%s', self.device, self.name)
        data = getDictionary(sd.readline())
        devID = data['device_id']
        registerDevice(devID)

        count = 0

        while True:
            ## READ SERIAL INPUT
            try:
                line = sd.readline()
                logging.debug('%s: readline()==%s', self.device, line)

            except serial.SerialException, e:
                logging.debug('%s: SerialException raised: %s', self.device, e)
                logging.debug('%s: thread=%s', self.device, self.name)
                
                sql = "UPDATE device_registers SET valid=0 WHERE device_id=?"
                conn = sqlite3.connect(REGISTER_DB_PATH)
                conn.execute(sql, (devID,))
                conn.commit()
                conn.close()
                sd.close()
                sys.exit(0)

            data = getDictionary(line)

            write(data)

            ## LOG DEVICE HEARTBEAT
            count += 1
            if count == 360:
                logging.info(' %s/%s: Heartbeat(%s)', self.device, data['device_id'], count)
                count = 0


## EOF
