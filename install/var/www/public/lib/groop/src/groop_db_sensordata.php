<?php

    require_once ('lib/groop/src/groop_constants.php');
    require_once ('lib/groop/src/groop_device_register.php');

    class DBSensordata {

        static function delete(DeviceRegister $register) {
            $tables = array($register->getGranularTable(), $register->getSummaryTable());
            
            try {
                $pdo = new PDO(Constants::SENSORDATA_DB);
            } catch (EXCEPTION $e) { die("Unable to connect: " . $e->getMessage()); }
            
            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                foreach ($tables as $table) {
                    $sql = "DROP TABLE " . $table;
                    $statement = $pdo->prepare($sql);
                    $statement->execute();
                }
                $pdo->commit();
                $statement->closeCursor();
                $pdo = null;
            } catch (EXCEPTION $e) {
                echo $e->getMessage();
                $pdo->rollBack();
                $pdo = null;
            }
        }

    }

?>