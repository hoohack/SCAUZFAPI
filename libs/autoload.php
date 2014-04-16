<?php
	function &load_class($class) {
    	static $_classes = array();

    	if(isset($_classes[$class])) {
    		return $_classes[$class];
    	}
    	if(file_exists(strtolower($class) . '.php')) {
    		require_once strtolower($class) . '.php';
    	}
    	else {
			die("No such ". $class. " class.");
		}
    	
    	$_classes[$class] = new $class();
    	return $_classes[$class];
    }

    function redirect($url) {
		echo '<script type="text/javascript">location.href="'.$url.'";</script>';
	}

    spl_autoload_register('load_class');
    spl_autoload_register('redirect');