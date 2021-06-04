<?php
    class DB
    {
        protected $db_host;
        protected $db_user;
        protected $db_pass;
        protected $db_name;

        // Loads the database login details from the config file
        public function __construct()
        {
            $config = config::getDBConfig();

            $this->db_host = $config->db_host;
            $this->db_user = $config->db_user;
            $this->db_pass = $config->db_pass;
            $this->db_name = $config->db_name;
        }

        // Database connection object
        private $dbConnectionObject;

        // Connects to the database with given parameters
        // Returns mysqli_stmt when it connects properly
        // to the database
        protected function connect($query): mysqli_stmt
        {
            // Instantiates a new mysql connection
            $this->dbConnectionObject = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

            // Checks if anything went wrong with the connection
            if($this->dbConnectionObject->connect_error) {
                $debugConfig = config::getDebugConfig();

                if($debugConfig->show_error)
                {
                    die('Connect Error (' . $this->dbConnectionObject->connect_errno . ') ' . $this->dbConnectionObject->connect_error);
                }
                else
                {
                    die('Something went wrong');
                }
            }

            // Returns a prepared statement with given query
            return $this->dbConnectionObject->prepare($query);
        }

        // Closes the database connection
        protected function close()
        {
            $this->dbConnectionObject->close();
        }
    }
?>
