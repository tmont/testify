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
	
	/**
	 * Bootstraps ezComponents
	 */
	require_once 'external/ezc/Base/base.php';
	spl_autoload_register('ezcBase::autoload');
	
	//set up default code coverage filters
	CoverageFilter::addDefaultFilters();

?>