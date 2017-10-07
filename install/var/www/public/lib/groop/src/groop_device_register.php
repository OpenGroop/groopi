<?php
    require_once ('lib/groop/src/groop_constants.php');

    class DeviceRegister {

        private $id             = '';
        private $alias          = '';
        private $valid          = '';
        private $granular_table = '';
        private $summary_table  = '';
        private $unit           = '';
 
        public function __construct($device_id) {
            
            $this->id = $device_id;
            
            try {
                $register_db = new PDO(Constants::REGISTER_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $register_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $register_db->beginTransaction();
                $statement = $register_db->prepare("SELECT * FROM device_registers WHERE device_id=?");
                $statement->execute(array($device_id));
                $statement->bindColumn('device_alias', $this->alias);
                $statement->bindColumn('granular_table', $this->granular_table);
                $statement->bindColumn('summary_table', $this->summary_table);
                $statement->bindColumn('valid', $this->valid);
                $statement->bindColumn('uom', $this->unit);
                $statement->fetch(PDO::FETCH_BOUND);
                $statement    = null;
                $register_db  = null;
            } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }
        }

        public function getId() {
            return $this->id;
        }

        public function getAlias() {
            return $this->alias;
        }

        public function getValid() {
            return $this->valid;
        }

        public function getGranularTable() {
            return $this->granular_table;
        }

        public function getSummaryTable() {
            return $this->summary_table;
        }

        public function getUnit() {
            return $this->unit;
        }

    } 
?>  