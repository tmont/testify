<?php

	/**
	 * Bootstrapper for TUnit
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * @see Autoloader
	 */
	require_once 'util/Autoloader.php';
	
	Autoloader::loadClassMapFromFile(dirname(__FILE__) . '/manifest.php');
	spl_autoload_register('Autoloader::autoload');
	
	CoverageFilter::addDefaultFilters();

?>