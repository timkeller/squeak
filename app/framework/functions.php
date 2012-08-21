<?php

// -----------------------------------------
// Squeak Framework Autoloader
// -----------------------------------------
class Squeak_Autoloader
{
    /**
     * Registers Twig_Autoloader as an SPL autoloader.
     */
    static public function register()
    {
        spl_autoload_register(array(new self, 'autoload_squeak'));
    }

    /**
     * Handles autoloading of classes.
     *
     * @param string $class A class name.
     */
    static public function autoload_squeak($class)
    {
        if (0 === strpos($class, 'Twig')) {
            return;
        }

        if(file_exists(APP_ROOT . '/framework/' . strtolower($class) . '.php'))
		{
			require_once APP_ROOT . '/framework/' . strtolower($class) . '.php';
		}
		else if(file_exists(APP_ROOT . '/models/' . strtolower($class) . '.php'))
		{
			require_once APP_ROOT . '/models/' . strtolower($class) . '.php';
		}
		else
		{
			echo "The system autoloader could not load up the '".$class."' file.";
			exit;
		}	
    }
}

// -----------------------------------------
// Template Helper
// -----------------------------------------
class Tpl
{
    static public function add($key, $value)
    {
        global $template_vars;
        $template_vars[$key] = $value;
    }
}

// -----------------------------------------
// Router help
// -----------------------------------------
class Route 
{
    static public function error404($why)
    {
        echo "404<hr>";
        echo $why;
        exit;
    }
}
