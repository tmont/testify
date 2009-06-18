<?php

	require_once 'cli.php';
	require_once dirname(dirname(__FILE__)) . '/bootstrap.php';
	
	$switches = new CliSwitchCollection();

	array_shift($argv);
	$args = Cli::parseArgs($argv, $switches);
	
	//accumulate tests
	$tests = TestAccumulator::getTests($args['args'], true);
	
	$runner = new TestRunner(array(new TestSuite('Main Test Suite', $tests)), array(new ConsoleListener(/*ConsoleListener::VERBOSITY_HIGH*/)));
	$runner->run();
	exit(0);

?>