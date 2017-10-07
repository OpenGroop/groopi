<?php 
    class Constants {
        const SENSORDATA_DB = "sqlite:/srv/sqlite3/data/sensordata.db";
        const SYSTEM_DB     = "sqlite:/srv/sqlite3/data/system.db";
        const REGISTER_DB   = "sqlite:/srv/sqlite3/data/register.db";
        const USER_DB       = "sqlite:/srv/sqlite3/data/user.db";
        const TEMP_C        = "temp_c";
        const TEMP_F        = "temp_f";

        const AUTH_LOG      = "/var/log/lighttpd/sentry-auth.log";
    }
?>
