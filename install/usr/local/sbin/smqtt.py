#!/usr/bin/python

import logging
import ssl
import paho.mqtt.publish as publish

from sconstants import LOG_PATH

class SMQTT():
    
    @staticmethod
    def push(data):

        logging.basicConfig(filename=LOG_PATH,level=logging.DEBUG, format='[%(created)f] [%(asctime)s] [%(process)d] [%(filename)s] [%(levelname)s]: %(message)s')

        logging.info('smqtt.push() called')

        mqttHost = 'mosquitto.opengroop.org'
        mqttUser = 'TEST_05'
        mqttPass = '3rs1xxxjjq9kkk'
        mqttPort = 8883
        ca_cert  = '/etc/ssl/certs/DST_Root_CA_X3.pem'

        mqttAuth = {'username':mqttUser, 'password':mqttPass}
        mqttTls  = {'ca_certs':ca_cert, 'tls_version':ssl.PROTOCOL_TLSv1_2}

        topic = mqttUser + '/sensor/sthp/' + data['device_id']

        logging.debug('Topic:   %', topic)
        logging.debug('Payload: %', str(data))
        logging.debug('mqttHost: %', mqttHost)
        logging.debug('mqttUser: %', mqttUser)
        logging.debug('mqttPass: %', mqttPass)
        logging.debug('mqttPort: %', str(mqttPort))
        logging.debug('ca_cert:  %', ca_cert)
        logging.debug('mqttAuth: %', str(mqttAuth))

        try:
            publish.single(topic, str(data), hostname=mqttHost, auth=mqttAuth, port=mqttPort, tls=mqttTls, client_id=data['device_id'] )
        except Exception, e:
            logging.warn(e)
        

## EOF
