<?php

	/**
	 * ConsoleListener
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Test listener for console-initiated test
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class ConsoleListener extends TestListener {
		
		/**
		 * @var int
		 */
		protected $verbosity;
		
		/**
		 * The current line length
		 *
		 * @var int
		 */
		protected $currentLineLength;
		
		/**
		 * Maximum line length
		 *
		 * @var int
		 */
		const LINE_LENGTH      = 64;
		
		/**
		 * Low verbosity
		 *
		 * @var int
		 */
		const VERBOSITY_LOW    = 0;
		
		/**
		 * Normal verbosity
		 *
		 * @var int
		 */
		const VERBOSITY_MEDIUM = 1;
		
		/**
		 * High verbosity
		 *
		 * @var int
		 */
		const VERBOSITY_HIGH   = 2;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  int $verbosity
		 */
		public function __construct($verbosity = self::VERBOSITY_MEDIUM) {
			$this->verbosity = intval($verbosity);
			$this->currentLineLength = 0;
		}
		
		/**
		 * Writes to stdout
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $text
		 */
		protected function out($text) {
			fwrite(STDOUT, $text);
		}
		
		/**
		 * Writes to stderr
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $text
		 */
		protected function err($text) {
			fwrite(STDERR, $text);
		}
		
		/**
		 * Writes a test method result
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    out()
		 * 
		 * @param  string $text
		 */
		private function writeTestMethodResult($text) {
			if ($this->currentLineLength >= self::LINE_LENGTH) {
				$this->out("\n");
				$this->currentLineLength = 0;
			}
			
			$this->out($text);
			$this->currentLineLength = strlen($text);
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestRunner $runner
		 */
		public function beforeTestRunner(TestRunner $runner) {
			$this->out(Product::getVersionString() . "\n");
			$this->out('  by ' . Product::AUTHOR . "\n\n");
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestRunner::getEndTime()
		 * @uses    TestRunner::getStartTime()
		 * @uses    TestRunner::getTestCount()
		 * 
		 * @param  TestRunner $runner
		 */
		public function afterTestRunner(TestRunner $runner) {
			$elapsedTime = $runner->getEndTime() - $runner->getStartTime();
			$testCount   = $runner->getTestCount();
			
			$suites      = $testCount['suite'] === 1  ? '1 test suite'  : $testCount['suite'] . ' test suites';
			$cases       = $testCount['case'] === 1   ? '1 test case'   : $testCount['case']  . ' test cases';
			$methods     = $testCount['method'] === 1 ? '1 test method' : $testCount['method'] . ' test methods';
			
			$this->out("Ran $suites, $cases and $methods in " . round($elapsedTime, 3) . ' seconds' . "\n");
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestSuite $suite
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestSuite $suite
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestCase $test
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestCase $test
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestCase $test
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestCase $test
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
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
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $message
		 */
		public function onFrameworkError($message) {
			$this->err('ERROR: ' . $message . "\n");
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $message
		 */
		public function onFrameworkWarning($message) {
			if ($this->verbosity > self::VERBOSITY_LOW) {
				$this->out('WARNING: ' . $message . "\n");
			}
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    CombinedTestResult::getPassedTestResults()
		 * @uses    CombinedTestResult::getFailedTestResults()
		 * @uses    CombinedTestResult::getErredTestResults()
		 * @uses    CombinedTestResult::getIgnoredTestResults()
		 * 
		 * @param  mixed $result
		 * @throws InvalidArgumentException
		 */
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