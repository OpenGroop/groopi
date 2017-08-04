import logging
import os
import sqlite3
from sconstants import LOG_PATH
from sconstants import REGISTER_DB_PATH
from sconstants import SENSORDATA_DB_PATH

logging.basicConfig(filename=LOG_PATH,level=logging.INFO, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s')


def getDPIndxs(ldrList):
	lst = []
	i = 0
	for value in ldrList:
		if value < 512:
			lst.append(i)
		i += 1
	return lst

def getPPIndxs(ldrList):
	lst = []
	i = 0
	for value in ldrList:
		if value >= 512:
			lst.append(i)
		i += 1
	return lst

def getAOValue(valueList):
	f = 0.0
	for value in valueList:
		f += value
	f = f / len(valueList)
	return f

def getAPValue(valueList, indexList):
	logging.debug('valueList length = %s', len(valueList))
	logging.debug('indexList length = %s', len(indexList))
	f = 0.0
	for i in indexList:
		f += valueList[i]
	logging.debug('f = %s', f)
	f = f / len(indexList)
	logging.debug('f = %s', f)
	return f

def getMaxValue(valueList):
	f = 0.0
	for value in valueList:
		if value > f:
			f = value
	return f

def getMinValue(valueList):
	f = 1023.0
	for value in valueList:
		if value < f:
			f = value
	return f


class Generator():
	def __init__(self, sqlRow):
		self.device_id = sqlRow['device_id']
		self.granular_table = sqlRow['granular_table']
		self.summary_table  = sqlRow['summary_table']

		logging.info(' %s: Initializing summary generator', self.device_id)

		sql = "SELECT temp_c, temp_f, humidity, ldr FROM " + self.granular_table
		sql =  sql + " WHERE timestamp BETWEEN strftime('%s', 'now', '-24 hours') AND strftime('%s', 'now')" 
		conn = sqlite3.connect(SENSORDATA_DB_PATH)
		conn.row_factory = sqlite3.Row
		c = conn.cursor()
		c.execute(sql)
		sqlResult = c.fetchall()
		c.close()
		conn.close()

		logging.debug('%s: Query length = %s', self.device_id, len(sqlResult))
		logging.debug('%s: Row keys = %s', self.device_id, sqlResult[0].keys())

		tempListC = []
		tempListF = []
		humList   = []
		ldrList   = []

		for row in sqlResult:
			tempListC.append(float(row['temp_c']))
			tempListF.append(float(row['temp_f']))
			humList.append(float(row['humidity']))
			ldrList.append(int(row['ldr']))

		ppIndxs = getPPIndxs(ldrList)
		dpIndxs = getDPIndxs(ldrList)

		self.taov  = round(getAOValue(tempListC),2)
		self.tappv = round(getAPValue(tempListC, ppIndxs),2)
		self.tadpv = round(getAPValue(tempListC, dpIndxs),2)
		self.tmaxv = round(getMaxValue(tempListC),2)
		self.tminv = round(getMinValue(tempListC),2)

		self.haov  = round(getAOValue(humList),2)
		self.happv = round(getAPValue(humList, ppIndxs),2)
		self.hadpv = round(getAPValue(humList, dpIndxs),2)
		self.hmaxv = round(getMaxValue(humList),2)
		self.hminv = round(getMinValue(humList),2)

	def summarize(self):
		logging.info(' %s: Summarizing device', self.device_id)
		sqlInsert = "INSERT INTO {} (timestamp, taov, tappv, tadpv, tmaxv, tminv, haov, happv, hadpv, hmaxv, hminv)".format(self.summary_table)
		sqlValues = " VALUES (strftime('%s', 'now'), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
		sqlStmnt = sqlInsert + sqlValues
		values = [self.taov, self.tappv, self.tadpv, self.tmaxv, self.tminv, self.haov, self.happv, self.hadpv, self.hmaxv, self.hminv]
		conn = sqlite3.connect(SENSORDATA_DB_PATH)
		conn.execute(sqlStmnt, values)
		conn.commit()
		conn.close()
		return True


