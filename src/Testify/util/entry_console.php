<?php

	/**
	 * Entry point for console test runner
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * @see Cli
	 */
	require_once 'cli.php';
	
	/**
	 * Bootstraps Testify
	 */
	require_once dirname(dirname(__FILE__)) . '/bootstrap.php';
	
	/**
	 * Prints usage
	 *
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	function usage() {
		$usage = new Usage(
			Product::NAME . ' ' . Product::VERSION . ' (' . Product::DATE . ')',
			'testify',
			'Command line test runner',
			'Tommy Montgomery',
			'2009'
		);
		
		global $switches;
		$usage->setSwitches($switches);
		
		echo $usage;
	}
	
	global $switches;
	$switches = new CliSwitchCollection();
	$switches->addSwitch(new CliSwitch(null,                null, true, '<files>', 'Files and/or directories to parse for test cases'))
	         ->addSwitch(new CliSwitch('help',              'h',  false, null,     'Display this help message (also --usage)'))
	         ->addSwitch(new CliSwitch('usage',             null, false, null,     'Display this help message'))
	         ->addSwitch(new CliSwitch('recursive',         null, false, null,     'Recurse into subdirectories'))
	         ->addSwitch(new CliSwitch('bootstrap',         'b',  false, 'file',   'File to include before tests are run'))
	         ->addSwitch(new CliSwitch('coverage-html',     null, false, 'dir',    'Generate code coverage report in HTML (requires xdebug)'))
	         ->addSwitch(new CliSwitch('coverage-renderer', null, false, 'type',   'Use with coverage-html to render code coverage graphs; png is the only type supported right now'))
	         ->addSwitch(new CliSwitch('coverage-console',  null, false, null,     'Generate code coverage report suitable for console viewing'));

	array_shift($argv);
	$args = Cli::parseArgs($argv, $switches);
	
	$options = $args['switches'];
	$args    = $args['args'];
	
	if (isset($options['help']) || isset($options['usage'])) {
		usage();
		exit(0);
	}
	
	if (empty($args)) {
		usage();
		exit(1);
	}
	
	$runner = new ConsoleTestRunner();
	$runner->setOptions($options);
	
	$runner->setFiles($args)
	       ->addListener(new ConsoleListener())
	       ->run();
	
	exit(0);

?>