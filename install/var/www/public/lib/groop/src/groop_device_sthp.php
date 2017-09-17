<?php
/* =======================================================
// HELPER CLASS TO HANDLE QUERIES TO STHP DATABASES 
// HANDLES REQUESTS TO BOTH GRANULAR AND SUMMARY TABLES
// ALL FUNCTIONS ARE STATIC
// ======================================================*/
	require_once ('lib/groop/src/groop_constants.php');
	require_once ('lib/groop/src/groop_device_register.php');

	class STHP extends DeviceRegister {

		public function __construct($device_id) {
			parent::__construct($device_id);
		}

		/* ===================================================
		// GETS LAST READINGS FROM DEVICE'S GRANULAR DATABASE
		// RETURNS: ARRAY()
		// ==================================================*/
		public function getCurrentReadings() {
			$readings = [
				'humidity'  => '',
				'ldr'       => '',
				'temp'      => '',     
				'timestamp' => ''
			];			

			try {
		        $sensordata_db = new PDO(Constants::SENSORDATA_DB);
		    } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

 			   // SELECT CURRENT TEMPERATURE (CELSIUS), HUMIDITY, AND LIGHT OUTPUT FOR DEVICE 
		    try {
		        $sensordata_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		        $sensordata_db->beginTransaction();
		        $sql = "SELECT timestamp,".$this->getUnit().", humidity, ldr FROM ".$this->getGranularTable().
		        " WHERE id = (SELECT MAX(id) FROM ".$this->getGranularTable().") ";
		        $statement = $sensordata_db->query($sql);
		        $statement->bindColumn($this->getUnit(), $readings['temp']);
		        $statement->bindColumn('humidity', $readings['humidity']);
		        $statement->bindColumn('ldr', $readings['ldr']);
		        $statement->bindColumn('timestamp', $readings['timestamp']);
		        $statement->fetch();
		        $statement = null;
		        $sensordata_db = null;
		    } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }

		    if ($readings['ldr'] == 0) { $readings['ldr'] = 'OFF';}
		    else                       { $readings['ldr'] = 'ON' ;}

		    $readings['timestamp'] = date("l, F j, g:i:s a", $readings['timestamp']);
		    return $readings;
		}

		/* =====================================================
		// GET GRANULAR READING BASED ON TIMEFRAME AND INTERVAL
 		// $timeframe: time span of readings (hours)
		// $interval:  interval between readings
		// RETURNS: 2D ARRAY()
		// ====================================================*/
		public function getGranularReading($timeframe, $interval) {
			try {
			    $database = new PDO(Constants::SENSORDATA_DB);
			} catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }
			
			try {
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $database->beginTransaction();
                $sql = "SELECT timestamp,".$this->getUnit().", humidity, ldr FROM ".$this->getGranularTable().
                    " WHERE timestamp BETWEEN strftime('%s', 'now', '-".$timeframe.
                    " hours') AND strftime('%s','now') AND id % ".$interval." = 0";
                $statement = $database->query($sql);
                $result = $statement->fetchAll();
                $statement->closeCursor();
                $statement = null;
                $database = null;
            } catch (EXCEPTION $e) { $database->rollback(); echo 'FAILED: ' . $e->getMessage(); }
            return $result;
		}
	}	
?>