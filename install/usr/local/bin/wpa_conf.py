#!/usr/bin/python
import getopt
import subprocess
import sys

conf = '/etc/wpa_supplicant/wpa_supplicant.conf'

def main(argv):
    ssid = ''
    psk  = ''

    try:
        opts, args = getopt.getopt(argv, "s:p:")
    except getopt.GetOptError:
        print "Invalid argument(s)"
        sys.exit(2)

    for opt, arg in opts:
        if opt == '-s':
            ssid = arg
        elif opt == '-p':
            psk = arg
    s = "network={\r\n\tssid=\"" + ssid +"\"\r\n\tpsk=\"" + psk + "\"\r\n}\r\n"

    with open(conf, 'a') as file:
        file.write(s)

    subprocess.call(['wpa_cli', 'reconfigure'])

    sys.exit(0)

if __name__ == "__main__":
    main(sys.argv[1:])


