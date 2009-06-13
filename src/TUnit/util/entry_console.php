<?php

	require_once 'cli.php';
	require_once dirname(dirname(__FILE__)) . '/bootstrap.php';
	
	$switches = new CliSwitchCollection();
	//$switches->AddSwitch(new CliSwitch('ini-directive', 'd', false, 'key="value"', 'php.ini directives'))

	array_shift($argv);
	$args = Cli::parseArgs($argv, $switches);
	
	//accumulate tests
	$tests = array();
	foreach ($args['args'] as $arg) {
		$arg = realpath($arg);
		if (is_file($arg)) {
			require_once $arg;
			$testClass = basename($arg, '.php');
			if (!class_exists($testClass)) {
				throw new Exception('The class "' . $testClass . '" does not exist in the file ' . $arg);
			}
			
			$testClass = new $testClass($testClass);
			if ($testClass instanceof Testable) {
				$tests[] = $testClass;
			}
		} else if (is_dir($arg)) {
			foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($arg)) as $file) {
				if ($file->isFile() && strpos($file->getPathName(), '.') === false) {
					$file = $file->getPathName();
					require_once $file;
					$testClass = basename($file, '.php');
					if (class_exists($testClass)) {
						continue;
					}
					
					$testClass = new $testClass($testClass);
					if ($testClass instanceof Testable) {
						$tests[] = $testClass;
					}
				}
			}
		}
	}
	
	
	$runner = new TestRunner(array(new TestSuite('Main Test Suite', $tests)), array(new ConsoleListener()));
	$runner->runAndPublish();
	exit(0);

?>