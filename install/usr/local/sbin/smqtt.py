#!/usr/bin/python

import logging
import sqlite3
import ssl
import paho.mqtt.client as paho

import smqtt_db_helper

from sconstants import LOG_PATH
from sconstants import SYSTEM_DB_PATH


class SMQTT():
    
    @staticmethod
    def push(data):

        logging.basicConfig(filename=LOG_PATH,level=logging.DEBUG, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s')

        logging.info('smqtt.push() called')

        row = SMQTT_DB_Helper.getAll()

        if row['enabled'] == 0:
            return

        def on_connect(mqttc, obj, flags, rc):
            if rc == paho.CONNACK_ACCEPTED:
                if row['conn_status'] != paho.CONNACK_ACCEPTED:
                    SMQTT_DB_HELPER.setConnStatus(paho.CONNACK_ACCEPTED)

            if rc == paho.CONNACK_REFUSED_PROTOCOL_VERSION:
                if row['conn_status'] != paho.CONNACK_REFUSED_PROTOCOL_VERSION:
                    SMQTT_DB_HELPER.setConnStatus(paho.CONNACK_REFUSED_PROTOCOL_VERSION)

            if rc == paho.CONNACK_REFUSED_IDENTIFIER_REJECTED:
                if row['conn_status'] != paho.CONNACK_REFUSED_IDENTIFIER_REJECTED:
                    SMQTT_DB_HELPER.setConnStatus(paho.CONNACK_REFUSED_IDENTIFIER_REJECTED)

            if rc == paho.CONNACK_REFUSED_SERVER_UNAVAILABLE:
                if row['conn_status'] != paho.CONNACK_REFUSED_SERVER_UNAVAILABLE:
                    SMQTT_DB_HELPER.setConnStatus(paho.CONNACK_REFUSED_SERVER_UNAVAILABLE)

            if rc == paho.CONNACK_REFUSED_BAD_USERNAME_PASSWORD:
                if row['conn_status'] != paho.CONNACK_REFUSED_BAD_USERNAME_PASSWORD:
                    SMQTT_DB_HELPER.setConnStatus(paho.CONNACK_REFUSED_BAD_USERNAME_PASSWORD)

            if rc == paho.CONNACK_REFUSED_NOT_AUTHORIZED:
                if row['conn_status'] != paho.CONNACK_REFUSED_NOT_AUTHORIZED:
                    SMQTT_DB_HELPER.setConnStatus(paho.CONNACK_REFUSED_NOT_AUTHORIZED)

        def on_publish(mqttc, obj, mid):

        ca_cert  = '/etc/ssl/certs/DST_Root_CA_X3.pem'

        mqttAuth = {'username': row['user'], 'password': row['password']}
        mqttTls  = {'ca_certs':ca_cert, 'tls_version':ssl.PROTOCOL_TLSv1_2}

        topic = row['user'] + '/sensor/sthp/' + data['device_id']

        # logging.debug('topic:   %', topic)
        # logging.debug('payload: %', str(data))
        # logging.debug('mqtt host: %', row['host'])
        # logging.debug('mqtt user: %', row['user'])
        # logging.debug('mqtt port: %', str(row['port']))
        # logging.debug('ca_cert:  %', ca_cert)
        # logging.debug('mqttAuth: %', str(mqttAuth))

        try:
            publish.single(topic, str(data), hostname=row['host'], auth=mqttAuth, port=row['port'], tls=mqttTls, client_id=data['device_id'] )
        except Exception, e:
            logging.warn(e)
            SMQTT_DB_Helper.setConnStatus(-1)
## EOF
