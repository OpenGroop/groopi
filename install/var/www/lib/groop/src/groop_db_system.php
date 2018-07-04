<?php
    require_once ('../lib/groop/src/groop_constants.php');

    class DBSystem {

        static function getUsbStatus() {
            $valid = '';
            try {
                $system_db = new PDO(Constants::SYSTEM_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $system_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $system_db->beginTransaction();
                $statement = $system_db->prepare('SELECT valid FROM usb WHERE ROWID=1');
                $statement->execute();
                $statement->bindColumn('valid', $valid);
                $statement->fetch(PDO::FETCH_BOUND);
                $statement = null;
                $system_db = null;
            } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }
            return $valid;
        }

        static function getMQTTSettings() {
            $mqtt = [
                'host'        => '',
                'port'        => '',
                'acct_id'     => '',
                'password'    => '',
                'enable'      => '',
                'conn_status' => ''
            ];

            try {
                $system_db = new PDO(Constants::SYSTEM_DB);
            } catch (Exception $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $system_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $system_db->beginTransaction();
                $statement = $system_db->prepare('SELECT * FROM mqtt WHERE ROWID=1');
                $statement->execute();
                $statement->bindColumn('host',        $mqtt['host']);
                $statement->bindColumn('port',        $mqtt['port']);
                $statement->bindColumn('acct_id',     $mqtt['acct_id']);
                $statement->bindColumn('password',    $mqtt['password']);
                $statement->bindColumn('enable',     $mqtt['enable']);
                $statement->bindColumn('conn_status', $mqtt['conn_status']);
                $statement->fetch(PDO::FETCH_BOUND);
                $statement = null;
                $system_db = null;
            } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }

            return $mqtt;
        }

        static function setMQTTSettings($settings) {
            try {
                $system_db = new PDO(Constants::SYSTEM_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $system_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $system_db->beginTransaction();
                $sql = "UPDATE mqtt SET host=?, port=?, acct_id=?, password=?, enable=?, conn_status=? WHERE ROWID=1";
                $statement = $system_db->prepare($sql);
                $statement->execute($settings);
                $system_db->commit();
                $statement = null;
                $system_db = null;
            } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }
        }

    }
?>
