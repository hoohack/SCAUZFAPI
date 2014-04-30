<?php
    /*
    *load_class function
    *@param class string(类名)
    *加载类
    *@author hhq 
    */
	function &load_class($class) {
    	static $_classes = array();

    	if(isset($_classes[$class])) {
    		return $_classes[$class];
    	}
    	if(file_exists(strtolower($class) . '.php')) {
    		require strtolower($class) . '.php';
    	}else if(file_exists('./libs/' . strtolower($class) . '.php')) {
            require './libs/' . strtolower($class) . '.php';
        }else if(file_exists('./Models/' . strtolower($class) . '.class.php')) {
            require './Models/' . strtolower($class) . '.class.php';
        }
    	else {
			die("No such ". $class. " class.");
		}
    	
    	$_classes[$class] = new $class();
    	return $_classes[$class];
    }

    /*
    *redirect function
    *@param url string (跳转URL)
    *跳转页面
    *@author hhq
    */
    function redirect($url) {
		echo '<script type="text/javascript">location.href="'.$url.'";</script>';
	}

    //注册全局函数
    spl_autoload_register('load_class');
    spl_autoload_register('redirect');