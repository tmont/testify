<?php

	/**
	 * Entry point for console test runner
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * @see Cli
	 */
	require_once 'cli.php';
	
	/**
	 * Bootstraps TUnit
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
			'tunit',
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
	$switches->addSwitch(new CliSwitch(null,        null, true, '<files>', 'Files and/or directories to parse for test cases'))
	         ->addSwitch(new CliSwitch('help',      'h',  false, null,     'Display this help message (also --usage)'))
	         ->addSwitch(new CliSwitch('usage',     null, false, null,     'Display this help message'))
	         ->addSwitch(new CliSwitch('recursive', null, false, null,     'Recurse into subdirectories'))
	         ->addSwitch(new CliSwitch('bootstrap', 'b',  false, 'file',   'File to include before tests are run'));

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
	
	//accumulate tests
	$tests = TestAccumulator::getTests($args, isset($options['recursive']));
	
	if (empty($tests)) {
		fwrite(STDERR, 'Found no TestCase subclasses in the given files');
		exit(1);
	}
	
	if (isset($options['bootstrap'])) {
		if (!is_file($options['bootstrap'])) {
			fwrite(STDERR, 'Bootstrap file given (' . $options['bootstrap'] . ') is not a file');
			exit(1);
		}
		
		require_once $options['bootstrap'];
	}
	
	$runner = new ConsoleTestRunner(
		array(new TestSuite('Main Test Suite', $tests)),
		array(new ConsoleListener()),
		$options
	);
	
	$runner->run();
	exit(0);

?>