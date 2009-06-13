<?php

	require_once 'util/Autoloader.php';
	
	Autoloader::loadClassMap(dirname(__FILE__) . '/manifest.php');
	spl_autoload_register('Autoloader::autoload');

?>