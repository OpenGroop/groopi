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

        static function getMQTTStatus() {
            $enabled     = '';
            $conn_status = '';

            try {
                $system_db = new PDO(Constants::SYSTEM_DB);
            } catch (Exception $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $system_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $system_db->beginTransaction();
                $statement = $system_db->prepare('SELECT enabled conn_status FROM mqtt WHERE ROWID=1');
                $statement-execute();
                $statement->bindColumn('enabled', $enabled);
                $statement->bindColumn('conn_status', $conn_status)
                $statement->fetchBound(PDO::FETHCH_BOUND);
                $statement = null;
                $sustem_db = null;
            } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }
        }
    }
?>
