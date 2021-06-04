<?php
    class AutoLoader
    {
        // Instantiates a new class and
        // loads the file according to
        // the class
        public static function register()
        {
            spl_autoload_register(function ($className)
            {
                $file = 'php/' . strtolower($className) . '.php';
                if(file_exists($file))
                {
                    require $file;
                    return true;
                }
                return false;
            });
        }
    }

    AutoLoader::register();
    $init = new Init();
?>
