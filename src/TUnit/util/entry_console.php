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
	$switches->addSwitch(new CliSwitch(null, null, true, '<files>', 'Files and/or directories to parse for test cases'))
	         ->addSwitch(new CliSwitch('help', 'h', false, null, 'Display this help message (also --usage)'))
	         ->addSwitch(new CliSwitch('recursive', null, false, null, 'Recurse into subdirectories'))
	         ->addSwitch(new CliSwitch('bootstrap', 'b', false, 'file', 'File to include before each test'));

	array_shift($argv);
	$args = Cli::parseArgs($argv, $switches);
	//print_r($args); exit;
	
	$localSwitches = $args['switches'];
	$localArgs     = $args['args'];
	
	if (isset($localSwitches['help'])) {
		usage();
		exit(0);
	}
	
	if (empty($localArgs)) {
		usage();
		exit(1);
	}
	
	//accumulate tests
	$tests = TestAccumulator::getTests($localArgs, isset($localSwitches['recursive']));
	
	if (empty($tests)) {
		fwrite(STDERR, 'Found no TestCase subclasses in the given files');
		exit(1);
	}
	
	$runner = new ConsoleTestRunner(array(new TestSuite('Main Test Suite', $tests)), array(new ConsoleListener(/*ConsoleListener::VERBOSITY_HIGH*/)), $localSwitches);
	$runner->run();
	exit(0);

?>