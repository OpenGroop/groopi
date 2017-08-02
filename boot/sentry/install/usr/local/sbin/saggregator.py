#!/usr/bin/python

import logging
import os
import sqlite3
import ssummary
from sconstants import LOG_PATH
from sconstants import REGISTER_DB_PATH

logging.basicConfig(filename=LOG_PATH,level=logging.INFO, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s')
logging.info(" Aggregating process started")


sql = "SELECT device_id, granular_table, summary_table, valid FROM device_registers"
db_conn = sqlite3.connect(REGISTER_DB_PATH)
db_conn.row_factory = sqlite3.Row
c = db_conn.cursor()
sqlResult = c.execute(sql).fetchall()
c.close()
db_conn.close()

logging.info(' %s tables found', len(sqlResult))

count = 0
for row in sqlResult:
	if(row['valid'] == 1):
		logging.info(' Begin processing %s', row['device_id'])
		sg = ssummary.Generator(row)
		sg.summarize()
		logging.info(' Finished processing %s', row['device_id'])
		count += 1

logging.info(' %s tables processed', count)

exit(0)
