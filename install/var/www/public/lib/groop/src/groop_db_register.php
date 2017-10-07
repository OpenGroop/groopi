<?php 
/* ======================================
// DATABASE HELPER CLASS FOR REGISTER.DB
// ALL FUNCTIONS ARE STATIC
// =====================================*/  
require_once ('lib/groop/src/groop_constants.php');

class DBRegister {

    const ID             = 'id';
    const DEV_ID         = 'device_id';
    const ALIAS          = 'device_alias';
    const GRANULAR_TABLE = 'granular_table';
    const SUMMARY_TABLE  = 'summary_table';
    const VALID          = 'valid';

        /* ========================
        // GET ALL REGISTER ROWS
        // RETURNS: NESTED ARRAY()
        // ========================*/
        static function getAll() {
            try {
                $database = new PDO(Constants::REGISTER_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $database->beginTransaction();
                $sql = "SELECT  * FROM device_registers ORDER BY device_alias";
                $statement = $database->query($sql);
                $result = $statement->fetchAlL(PDO::FETCH_ASSOC);
                $statement->closeCursor();
                $statement = null;
                $database = null;
            } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }

            return $result;
        }
        /* ===================
        // GET ALL DEVICE IDS
        // RETURNS: ARRAY()
        // ===================*/
        static function getIds() {
            try {
                $database = new PDO(Constants::REGISTER_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $database->beginTransaction();
                $sql = "SELECT  device_id FROM device_registers ORDER BY device_alias";
                $statement = $database->query($sql);
                $result = $statement->fetchAlL(PDO::FETCH_ASSOC);
                $statement->closeCursor();
                $statement = null;
                $database = null;
            } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }

            $ids = [];

            foreach ($result as $array) {
                $ids[] = $array['device_id'];
            }
            
            return $ids;
        }
        
        /* ====================================
        // UPDATES DEVICE ALIAS
        // $device_id:    device to be updated
        // $device_alias: updated device alias
        // ====================================*/
        static function updateAlias($device_id, $device_alias) {
            try {
                $pdo = new PDO(Constants::REGISTER_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "UPDATE device_registers SET device_alias=? WHERE device_id=?";
                $stmnt = $pdo->prepare($sql);
                $stmnt->execute(array($device_alias, $device_id));
                $pdo->commit();
                $pdo = null;
            } catch (EXCEPTION $e) { $pdo->rollback(); echo 'FAILED: ' . $e->getMessage(); }
        }

        /* =============================================
        // UPDATES DEVICE UNIT OF MEASURE
        // $device_id:   device to be updated
        // $device_unit: updated device unit of measure
        // =============================================*/
        static function updateUnit($device_id, $device_unit) {
            try {
                $pdo = new PDO(Constants::REGISTER_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "UPDATE device_registers SET uom=? WHERE device_id=?";
                $stmnt = $pdo->prepare($sql);
                $stmnt->execute(array($device_unit, $device_id));
                $pdo->commit();
                $pdo = null;
            } catch (EXCEPTION $e) { $pdo->rollback(); echo 'FAILED: ' . $e->getMessage(); }
        }

        /* =================================
        // DELETES DEVICE FROM REGISTER
        // $device_id: device to be deleted
        // =================================*/
        static function delete($device_id) {
            try {
                $pdo = new PDO(Constants::REGISTER_DB);
            } catch (EXCEPTION $e) { die("Unable to connect: " . $e->getMessage()); }
            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "DELETE FROM device_registers WHERE device_id=?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($device_id));
                $pdo->commit();
                $statement->closeCursor();
                $pdo = null;
            } catch (EXCEPTION $e) { $pdo->rollback(); echo 'FAILED: ' . $e->getMessage(); }
        }

    }
?>