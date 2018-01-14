<?php

    $nc = '(not connected)';

    function get_if_ip($interface) {
        global $nc;
        $ip = exec("ip addr show ".$interface." | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
        if (strlen($ip == 0)) {
            $ip = $nc;
        }
        return $ip;
    }

    function get_if_url($ipaddr) {
        global $nc;
        $url = '';
        if ($ipaddr == $nc) { 
            $url = $nc;
        } else {
            $url = 'https://' . $ipaddr;
        }
        return $url;
    }

    function get_remote_ip() {
        global $nc;
        $ip = exec('curl -s https://www.nancoo.org/ip-address.php | grep ip_address | sed "s/<[^>]*>//g"');
        if (strlen($ip == 0)) {
            $ip = $nc;
        }
        return $ip;
    }

    function get_local_ssid() {
        global $nc;
        $ssid = exec("iwgetid -r");
        if (strlen($ssid == 0)) {
            $ssid = $nc;
        }
        return $ssid;
    }

    function get_ap_ssid() {
        $ssid = exec("cat /etc/hostapd/hostapd.conf | grep ssid | grep -v ignore | cut -d = -f2");
        return $ssid;
    }

    function get_ap_pw() {
        $pw = exec("cat /etc/hostapd/hostapd.conf | grep passphrase | cut -d = -f2");
        return $pw;
    }

?>


