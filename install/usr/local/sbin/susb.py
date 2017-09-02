#!/usr/bin/python

import logging
import sqlite3
from sconstants import LOG_PATH
from sconstants import SYSTEM_DB_PATH

logging.basicConfig(filename=LOG_PATH,level=logging.INFO, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s')

class SUSB():
    @staticmethod
    def validate():
        logging.info(' validating USB drive')
        try:
            system_db = sqlite3.connect(SYSTEM_DB_PATH)
        except sqlite3.OperationalError, e:
            logging.info(' OperationalError: %s', e)
            return None

        try:
            sql = 'UPDATE usb SET valid=1 WHERE ROWID=1'
            cursor = system_db.cursor()
            cursor.execute(sql)
            system_db.commit()
            cursor.close()
            system_db.close()
        except sqlite3.OperationalError, e:
            logging.info(' OperationalError: %s', e)
            return None

        logging.info(' USB drive validated')

    @staticmethod
    def invalidate():
        logging.info(' invalidating USB drive')

        try:
            system_db = sqlite3.connect(SYSTEM_DB_PATH)
        except sqlite3.OperationalError, e:
            logging.info(' OperationalError: %s', e)
            return None

        try:
            sql = 'UPDATE usb SET valid=0 WHERE ROWID=1'
            cursor = system_db.cursor()
            cursor.execute(sql)
            system_db.commit()
            cursor.close()
            system_db.close()
        except sqlite3.OperationalError, e:
            logging.info(' OperationalError: %s', e)
            return None

        logging.info(' USB drive invalidated')