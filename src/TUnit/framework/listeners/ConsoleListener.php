<?php

	class ConsoleListener {
		
		private $verbosity;
		
		const VERBOSITY_LOW    = 0;
		const VERBOSITY_MEDIUM = 1;
		const VERBOSITY_HIGH   = 2;
		
		public function __construct($verbosity = self::VERBOSITY_MEDIUM) {
			$this->verbosity = intval($verbosity);
		}
		
		private function out($text) {
			fwrite(STDOUT, $text);
		}
		
		private function err($text) {
			fwrite(STDERR, $text);
		}
		
		public function beforeTestSuite(TestSuite $suite) {
			switch ($this->verbosity) {
				case self::VERBOSITY_HIGH:
					$this->out("\n" . $suite->getName() . "\n");
					break;
				case self::VERBOSITY_LOW:
				case self::VERBOSITY_MEDIUM:
				default:
					break;
			}
		}
		
		public function afterTestSuite(TestSuite $suite) {
			switch ($this->verbosity) {
				case self::VERBOSITY_HIGH:
					$this->out("\n");
					break;
				case self::VERBOSITY_LOW:
				case self::VERBOSITY_MEDIUM:
				default:
					break;
			}
		}
		
		public function beforeTestCase(TestCase $test) {
			switch ($this->verbosity) {
				case self::VERBOSITY_HIGH:
					$this->out('  ' . $test->getName() . "\n");
					break;
				case self::VERBOSITY_LOW:
				case self::VERBOSITY_MEDIUM:
				default:
					break;
			}
		}
		
		public function afterTestCase(TestCase $test) {
			switch ($this->verbosity) {
				case self::VERBOSITY_HIGH:
					$this->out("\n");
					break;
				case self::VERBOSITY_LOW:
				case self::VERBOSITY_MEDIUM:
				default:
					break;
			}
		}
		
		public function onTestCaseFailed(TestCase $test) {
			switch ($this->verbosity) {
				case self::VERBOSITY_HIGH:
					$this->out("  !!TEST CASE FAILED!!\n");
					break;
				case self::VERBOSITY_LOW:
				case self::VERBOSITY_MEDIUM:
				default:
					break;
			}
		}
		
		public function onTestCasePassed(TestCase $test) {
			switch ($this->verbosity) {
				case self::VERBOSITY_HIGH:
					$this->out("  test case passed\n");
					break;
				case self::VERBOSITY_LOW:
				case self::VERBOSITY_MEDIUM:
				default:
					break;
			}
		}
		
		public function beforeTestMethod(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_HIGH:
					$this->out('    ' . $method->getName() . "\n");
					break;
				case self::VERBOSITY_LOW:
				case self::VERBOSITY_MEDIUM:
				default:
					break;
			}
		}
		
		public function afterTestMethod(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_HIGH:
					$this->out("\n");
					break;
				case self::VERBOSITY_LOW:
				case self::VERBOSITY_MEDIUM:
				default:
					break;
			}
		}
		
		public function onTestMethodFailed(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_LOW:
					break;
				case self::VERBOSITY_HIGH:
				case self::VERBOSITY_MEDIUM:
				default:
					$this->out('F');
					break;
			}
		}
		
		public function onTestMethodPassed(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_LOW:
					break;
				case self::VERBOSITY_HIGH:
				case self::VERBOSITY_MEDIUM:
				default:
					$this->out('.');
					break;
			}
		}
		
		public function onTestMethodErred(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_LOW:
					break;
				case self::VERBOSITY_HIGH:
				case self::VERBOSITY_MEDIUM:
				default:
					$this->out('E');
					break;
			}
		}
		
		public function onTestMethodIgnored(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_LOW:
					break;
				case self::VERBOSITY_HIGH:
				case self::VERBOSITY_MEDIUM:
				default:
					$this->out('I');
					break;
			}
		}
		
		public function onFrameworkError($message) {
			$this->err('ERROR: ' . $message);
		}
		
		public function onFrameworkWarning($message) {
			if ($this->verbosity > self::VERBOSITY_LOW) {
				$this->out('WARNING: ' . $message);
			}
		}
		
	}

?>