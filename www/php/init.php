<?php
    class Init
    {
        // Initiates anything a page has to
        // run before showing.
        public function __construct()
        {
            $debugConfig = config::getDebugConfig();
            if(!$debugConfig->show_php_error)
            {
                ini_set('display_errors', 'Off');
            }

            session_start();
        }
    }
?>
