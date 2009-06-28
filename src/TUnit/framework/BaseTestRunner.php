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
		 * Adds tests in bulk
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    addTest()
		 * 
		 * @param  array $tests
		 * @return TestRunner
		 */
		public final function addTests(array $tests) {
			foreach ($tests as $test) {
				$this->addTest($test);
			}
			
			return $this;
		}
		
		/**
		 * Gets the value of an option
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $option
		 * @throws InvalidArgumentException
		 * @return mixed
		 */
		public final function getOption($option) {
			if (!array_key_exists($option, $this->options)) {
				throw new InvalidArgumentException('Option "' . $option . '" does not exist');
			}
			
			return $this->options[$option];
		}
		
		/**
		 * Parses options
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  array $unparsed The unparsed options
		 * @return array The parsed options
		 */
		protected abstract function parseOptions(array $unparsed);
		
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
		 * Called before runTests()
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		protected function preRun() {
			
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
			$this->preRun();
			
			foreach ($this->listeners as $listener) {
				$listener->beforeTestRunner($this);
			}
			
			$this->startTime = microtime(true);
			$results         = $this->runTests();
			$this->endTime   = microtime(true);
			
			$this->publishResults($results);
			
			foreach ($this->listeners as $listener) {
				$listener->afterTestRunner($this);
			}
			
			$this->postRun();
		}
		
		/**
		 * Called after runTests()
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		protected function postRun() {
		
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
		
	}

?>