<?php

	require_once 'cli.php';
	require_once dirname(dirname(__FILE__)) . '/bootstrap.php';
	
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
	         ->addSwitch(new CliSwitch('help', 'h', false, null, 'Display this help message (--usage is an alias)'))
	         ->addSwitch(new CliSwitch('recursive', null, false, null, 'Recurse into subdirectories'));

	array_shift($argv);
	$args = Cli::parseArgs($argv, $switches);
	
	if (isset($args['help'])) {
		usage();
		exit(0);
	}
	
	if (empty($args['args'])) {
		usage();
		exit(1);
	}
	
	//accumulate tests
	$tests = TestAccumulator::getTests($args['args'], isset($args['recursive']));
	
	if (empty($tests)) {
		fwrite(STDERR, 'Found no TestCase subclasses in the given files');
		exit(1);
	}
	
	$runner = new TestRunner(array(new TestSuite('Main Test Suite', $tests)), array(new ConsoleListener(/*ConsoleListener::VERBOSITY_HIGH*/)));
	$runner->run();
	exit(0);

?>