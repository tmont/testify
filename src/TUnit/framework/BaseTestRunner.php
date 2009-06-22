<?php

	/**
	 * TestRunner
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Base test runner
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	abstract class TestRunner implements RecursivelyCountable {
		
		/**
		 * The tests to run
		 *
		 * @var array
		 */
		protected $tests;
		
		/**
		 * The subscribing listeners
		 *
		 * @var array
		 */
		protected $listeners;
		
		/**
		 * @var array
		 */
		protected $options;
		
		/**
		 * Time the test runner began
		 *
		 * @var float
		 */
		private $startTime;
		
		/**
		 * Time the test runner ended
		 *
		 * @var float
		 */
		private $endTime;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  array $tests
		 * @param  array $listeners
		 * @param  array $options
		 */
		public function __construct(array $tests = array(), array $listeners = array(), array $options = array()) {
			$this->tests     = $tests;
			$this->listeners = $listeners;
			$this->options   = (!empty($options)) ? $this->parseOptions($options) : array();
			
			$this->startTime = -1;
			$this->endTime   = -1;
		}
		
		/**
		 * Gets the start time
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return float
		 */
		public final function getStartTime() {
			return $this->startTime;
		}
		
		/**
		 * Gets the end time
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return float
		 */
		public final function getEndTime() {
			return $this->endTime;
		}
		
		/**
		 * Gets the tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return array
		 */
		public final function getTests() {
			return $this->tests;
		}
		
		/**
		 * Sends a warning out to all listeners
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestListener::onFrameworkWarning()
		 * 
		 * @param  string $message
		 */
		public final function warn($message) {
			foreach ($this->listeners as $listener) {
				$listener->onFrameworkWarning($message);
			}
		}
		
		/**
		 * Sends an error message out to all listeners
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestListener::onFrameworkError()
		 * 
		 * @param  string $message
		 */
		public final function error($message) {
			foreach ($this->listeners as $listener) {
				$listener->onFrameworkError($message);
			}
		}
		
		/**
		 * Adds a test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  Testable $test
		 * @return TestRunner
		 */
		public final function addTest(Testable $test) {
			$this->tests[] = $test;
			return $this;
		}
		
		/**
		 * Adds a listener
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestListener $listener
		 * @return TestRunner
		 */
		public final function addListener(TestListener $listener) {
			$this->listeners[] = $listener;
			return $this;
		}
		
		/**
		 * Sets the options
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    parseOptions()
		 * 
		 * @param  array $options
		 * @return TestRunner
		 */
		public final function setOptions(array $options) {
			$this->options = $this->parseOptions($options);
			return $this;
		}
		
		/**
		 * Parses options
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getAllowableOptions()
		 * 
		 * @param  array $unparsed The unparsed options
		 * @throws {@link InvalidOptionException}
		 * @return array The parsed options
		 */
		protected final function parseOptions(array $unparsed) {
			$allowedOptions = $this->getAllowableOptions();
			$options = array_fill_keys(array_key($allowedOptions), null);
			foreach ($unparsed as $option => $value) {
				if (!isset($allowedOptions[$option])) {
					throw new InvalidOptionException($option);
				}
				if (gettype($value) !== $allowedOptions[$option]) {
					throw new InvalidOptionException($option, 'Expected a value of type ' . $allowedOptions[$option] . ', got ' . gettype($value));
				}
				
				$options[$option] = $value;
			}
			
			return $options;
		}
		
		/**
		 * Runs the tests and returns the results
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    warn()
		 * @uses    error()
		 * @uses    Testable::run()
		 * 
		 * @return array Array of {@link TestResult}s
		 */
		public function runTests() {
			$results = array();
			foreach ($this->tests as $test) {
				if ($test instanceof Testable) {
					try {
						$results[] = $test->run($this->listeners);
					} catch (TUnitException $e) {
						$this->error($e->getMessage());
					}
				} else {
					$this->warn('Unable to run test because it is not an instance of Testable (' . gettype($test) . ')');
				}
			}
			
			return $results;
		}
		
		/**
		 * Publishes test results
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestListener::publishTestResults()
		 * 
		 * @param  array $testResults Array of {@link TestResult}s
		 */
		public function publishResults(array $testResults) {
			foreach ($this->listeners as $listener) {
				$listener->publishTestResults($testResults);
			}
		}
		
		/**
		 * Runs all tests and publishes the results
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestListener::beforeTestRunner()
		 * @uses    publishResults()
		 * @uses    TestListener::afterTestRunner()
		 */
		public final function run() {
			foreach ($this->listeners as $listener) {
				$listener->beforeTestRunner($this);
			}
			
			$this->startTime = microtime(true);
			$this->publishResults($this->runTests());
			$this->endTime = microtime(true);
			
			foreach ($this->listeners as $listener) {
				$listener->afterTestRunner($this);
			}
		}
		
		/**
		 * Gets the number of tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return int
		 * @ignore
		 */
		public function count() {
			return count($this->tests);
		}
		
		/**
		 * Gets a detailed count of all tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    Util::countTests()
		 * 
		 * @return array The result of Util::countTests()
		 */
		public function getTestCount() {
			return Util::countTests($this->tests);
		}
		
		/**
		 * Gets all allowable options for this test runner
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 *
		 * @return array
		 */
		protected abstract function getAllowableOptions();
		
	}

?>