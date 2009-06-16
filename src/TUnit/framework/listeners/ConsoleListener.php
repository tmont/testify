<?php

	class ConsoleListener extends TestListener {
		
		private $verbosity;
		private $currentLineLength;
		
		const LINE_LENGTH      = 64;
		
		const VERBOSITY_LOW    = 0;
		const VERBOSITY_MEDIUM = 1;
		const VERBOSITY_HIGH   = 2;
		
		public function __construct($verbosity = self::VERBOSITY_MEDIUM) {
			$this->verbosity = intval($verbosity);
			$this->currentLineLength = 0;
		}
		
		private function out($text) {
			fwrite(STDOUT, $text);
		}
		
		private function err($text) {
			fwrite(STDERR, $text);
		}
		
		private function writeTestMethodResult($text) {
			if ($this->currentLineLength >= self::LINE_LENGTH) {
				$this->out("\n");
				$this->currentLineLength = 0;
			}
			
			$this->out($text);
			$this->currentLineLength = strlen($text);
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
				case self::VERBOSITY_MEDIUM:
					$this->out("\n");
					break;
				case self::VERBOSITY_LOW:
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
					$this->out('    ' . $method->getName() . ': ');
					break;
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
					$this->out('FAIL');
					break;
				case self::VERBOSITY_MEDIUM:
				default:
					$this->writeTestMethodResult('F');
					break;
			}
		}
		
		public function onTestMethodPassed(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_LOW:
					break;
				case self::VERBOSITY_HIGH:
					$this->out('pass');
					break;
				case self::VERBOSITY_MEDIUM:
				default:
					$this->writeTestMethodResult('.');
					break;
			}
		}
		
		public function onTestMethodErred(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_LOW:
					break;
				case self::VERBOSITY_HIGH:
					$this->out('ERROR');
					break;
				case self::VERBOSITY_MEDIUM:
				default:
					$this->writeTestMethodResult('E');
					break;
			}
		}
		
		public function onTestMethodIgnored(TestMethod $method) {
			switch ($this->verbosity) {
				case self::VERBOSITY_LOW:
					break;
				case self::VERBOSITY_HIGH:
					$this->out('ignored');
					break;
				case self::VERBOSITY_MEDIUM:
				default:
					$this->writeTestMethodResult('I');
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
		
		public function publishTestResult(TestResult $result) {
			$failure = $result->getFailure();
			if ($failure instanceof TestFailure) {
				$this->out("\n");
				$this->out("----------- FAILURE -----------\n");
				$this->out('Test name: ' . $result->getTest()->getName() . "\n");
				$this->out('Message:   ' . $failure->getMessage() . "\n\n");
				$this->out($failure->getStackTrace());
				$this->out("\n");
			}
		}
		
	}

?>