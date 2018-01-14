<?php

    require_once ('../lib/groop/src/groop_constants.php');
    require_once ('../lib/groop/src/groop_device_register.php');

    class DBSensordata {

        static function delete($tables) {
            $status   = '';

            try { $pdo = new PDO(Constants::SENSORDATA_DB); }
            catch (EXCEPTION $e) {
                $status = Constants::DB_CONN_ERR;
                return $status;
            }

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
                $status = Constants::DB_ACTION_OK;
            } catch (EXCEPTION $e) {
                $pdo->rollBack();
                $pdo = null;
                $status = Constants::DB_QUERY_ERR;
                return $status;
            }
            
            return $status;
        }

    }



?>


