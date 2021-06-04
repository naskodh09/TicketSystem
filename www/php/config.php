<?php
    class Config
    {
        // Loads all the database related config settings
        public static function getDBConfig()
        {
            $ini = (object) parse_ini_file('../config.ini', true);
            $config = $ini->database;

            return (object) $config;
        }

        // Loads all the debug related config settings
        public static function getDebugConfig()
        {
            $ini = (object) parse_ini_file('../config.ini', true);
            $config = $ini->debug;

            return (object) $config;
        }
    }
?>
