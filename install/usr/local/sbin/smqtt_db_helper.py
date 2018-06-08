#!/usr/bin/python

import logging
import sqlite3

from sconstants import LOG_PATH
from sconstants import SYSTEM_DB_PATH



logging.basicConfig(filename=LOG_PATH,level=logging.DEBUG, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s')

class SMQTT_DB_Helper():

    @staticmethod
    def getAll():
        conn = sqlite3.connect(SYSTEM_DB_PATH)
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        sql = "SELECT * FROM mqtt WHERE ROWID=1"
        cursor.execute(sql)
        row = cursor.fetchone()
        cursor.close()
        conn.close()
        return row

    @staticmethod
    def setConnStatus(status):
        conn = sqlite3.connect(SYSTEM_DB_PATH)
        sql = "UPDATE mqtt SET conn_status=? WHERE ROWID=1"
        cursor = conn.cursor()
        cursor.execute(sql, (status,))
        conn.commit()
        cursor.close()
        conn.close()
        

