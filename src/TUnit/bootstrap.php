<?php

	require_once 'util/Autoloader.php';
	
	Autoloader::loadClassMapFromFile(dirname(__FILE__) . '/manifest.php');
	spl_autoload_register('Autoloader::autoload');

?>