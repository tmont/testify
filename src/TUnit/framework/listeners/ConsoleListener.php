<?php

	class ConsoleListener extends TestListener {
		
		protected $verbosity;
		protected $currentLineLength;
		
		const LINE_LENGTH      = 64;
		
		const VERBOSITY_LOW    = 0;
		const VERBOSITY_MEDIUM = 1;
		const VERBOSITY_HIGH   = 2;
		
		public function __construct($verbosity = self::VERBOSITY_MEDIUM) {
			$this->verbosity = intval($verbosity);
			$this->currentLineLength = 0;
		}
		
		protected function out($text) {
			fwrite(STDOUT, $text);
		}
		
		protected function err($text) {
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
		
		public function beforeTestRunner(TestRunner $runner) {
			$this->out(Product::getVersionString() . "\n");
			$this->out('  by ' . Product::AUTHOR . "\n\n");
		}
		
		public function afterTestRunner(TestRunner $runner) {
			$elapsedTime = $runner->getEndTime() - $runner->getStartTime();
			$numTests = (count($runner) === 1) ? '1 test' : count($runner) . ' tests';
			$this->out('Ran ' . $numTests . ' in ' . round($elapsedTime, 3) . ' seconds' . "\n");
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
		
		public function publishTestResults($result) {
			if (is_array($result)) {
				$result = new CombinedTestResult($result);
			} else if ($result instanceof TestResult) {
				$result = new CombinedTestResult(array($result));
			}
		
			if (!($result instanceof CombinedTestResult)) {
				throw new InvalidArgumentException('Could not convert $result to a CombinedTestResult');
			}
			
			$passed  = $result->getPassedTestResults();
			$failed  = $result->getFailedTestResults();
			$erred   = $result->getErredTestResults();
			$ignored = $result->getIgnoredTestResults();
			
			//summary
			$total    = count($passed) + count($failed) + count($erred) + count($ignored);
			
			if ($total === 0) {
				return;
			}
			
			$countPad = strlen($total);
			$width    = 12 + $countPad + 3 + 6 + 3;
			
			$this->out("\n");
			$this->out('+' . str_repeat('-', $width - 2) . '+' . "\n");
			$this->out('| Passed  | ' . str_pad(count($passed), $countPad, ' ', STR_PAD_LEFT)  . ' | ' . str_pad(number_format(round(count($passed)  / $total * 100, 2), 2), 6, ' ', STR_PAD_LEFT) . '% |' . "\n");
			$this->out('+' . str_repeat('-', $width - 2) . '+' . "\n");
			$this->out('| Failed  | ' . str_pad(count($failed), $countPad, ' ', STR_PAD_LEFT)  . ' | ' . str_pad(number_format(round(count($failed)  / $total * 100, 2), 2), 6, ' ', STR_PAD_LEFT) . '% |' . "\n");
			$this->out('+' . str_repeat('-', $width - 2) . '+' . "\n");
			$this->out('| Erred   | ' . str_pad(count($erred), $countPad, ' ', STR_PAD_LEFT)   . ' | ' . str_pad(number_format(round(count($erred)   / $total * 100, 2), 2), 6, ' ', STR_PAD_LEFT) . '% |' . "\n");
			$this->out('+' . str_repeat('-', $width - 2) . '+' . "\n");
			$this->out('| Ignored | ' . str_pad(count($ignored), $countPad, ' ', STR_PAD_LEFT) . ' | ' . str_pad(number_format(round(count($ignored) / $total * 100, 2), 2), 6, ' ', STR_PAD_LEFT) . '% |' . "\n");
			$this->out('+' . str_repeat('-', $width - 2) . '+' . "\n");
			$this->out('+' . str_repeat('-', $width - 2) . '+' . "\n");
			$this->out('| Total   | ' . $total . ' | ' . '100.00% |' . "\n");
			$this->out('+' . str_repeat('-', $width - 2) . '+' . "\n");
			$this->out("\n");
			
			
			$padLength = 60;
			
			//failures
			if (count($failed) > 0) {
				$this->out("\n");
				$this->out('FAILURES');
				foreach ($failed as $i => $failure) {
					$this->out("\n");
					$this->out(str_pad(' ' . ($i + 1) . ' ', $padLength, '-', STR_PAD_BOTH) . "\n");
					$this->out('Test name: ' . $failure->getTest()->getName() . "\n");
					$this->out('Message:   ' . $failure->getFailure()->getMessage() . "\n\n");
					$this->out($failure->getFailure()->getStackTrace() . "\n");
					$this->out(str_repeat('-', $padLength) . "\n");
				}
			}
			
			//errors
			if (count($erred) > 0) {
				$this->out("\n");
				$this->out('ERRORS');
				foreach ($erred as $i => $error) {
					$this->out("\n");
					$this->out(str_pad(' ' . ($i + 1) . ' ', $padLength, '-', STR_PAD_BOTH) . "\n");
					$this->out('Test name: ' . $error->getTest()->getName() . "\n");
					$this->out('Message:   ' . $error->getFailure()->getMessage() . "\n\n");
					$this->out($error->getFailure()->getStackTrace() . "\n");
					$this->out(str_repeat('-', $padLength) . "\n");
				}
			}
			
			//ignores
			if (count($ignored) > 0) {
				$this->out("\n");
				$this->out('IGNORES');
				foreach ($ignored as $i => $ignore) {
					$this->out("\n");
					$this->out(str_pad(' ' . ($i + 1) . ' ', $padLength, '-', STR_PAD_BOTH) . "\n");
					$this->out('Test name: ' . $ignore->getTest()->getName() . "\n");
					$this->out('Message:   ' . $ignore->getFailure()->getMessage() . "\n");
					$this->out(str_repeat('-', $padLength) . "\n");
				}
			}
		}
		
	}

?>