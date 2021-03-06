#!/usr/bin/python

import logging
import sqlite3
import ssl
import paho.mqtt.publish as publish

from datetime import datetime

from sconstants import LOG_PATH
from sconstants import REGISTER_DB_PATH
from sconstants import SYSTEM_DB_PATH
from sconstants import CA_CERT
from smqtt_db_helper import SMQTT_DB_Helper

class SMQTT():

    @staticmethod
    def push(data):

        logging.basicConfig(filename=LOG_PATH,level=logging.INFO, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s')

        logging.debug('smqtt.push() called')

        row = SMQTT_DB_Helper.getAll()

        if row['enable'] == 0:
            logging.debug('mqtt enable: FALSE')
            return

        logging.debug('mqtt enable: TRUE')

        data.update({'datetime':str(datetime.today())})

        logging.debug('datetime: %s', data['datetime'])

        register_db = sqlite3.connect(REGISTER_DB_PATH)
        sql = "SELECT device_alias, uom FROM device_registers WHERE device_id =?"
        cursor = register_db.cursor()
        cursor.execute(sql,(data['device_id'],))
        rst = cursor.fetchone()
        cursor.close()
        register_db.close()

        data.update({'alias' : str(rst[0]) })
        data.update({'format': str(rst[1]) })

        logging.debug('alias: %s', data['alias'])
        logging.debug('format: %s', data['format'])


        ca_cert  = '/etc/ssl/certs/DST_Root_CA_X3.pem'

        mqttAuth = {'username': str(row['acct_id']), 'password': row['password']}
        mqttTls  = {'ca_certs':CA_CERT, 'tls_version':ssl.PROTOCOL_TLSv1_2}

        topic = str(row['acct_id']) + '/sensor/sthp/' + data['device_id']

        try:
            publish.single(topic, str(data), hostname=row['host'], auth=mqttAuth, port=row['port'], tls=mqttTls, client_id=data['device_id'] )
            SMQTT_DB_Helper.setConnStatus(0)
        except Exception, e:
            logging.warn(e)
            SMQTT_DB_Helper.setConnStatus(-1)
## EOF

