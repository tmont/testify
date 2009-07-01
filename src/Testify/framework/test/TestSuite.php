<?php

	/**
	 * TestSuite
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Represents a collection of test suites, test cases and/or
	 * test methods
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class TestSuite implements Testable {
		
		/**
		 * The name of this test
		 *
		 * @var string
		 */
		protected $name;
		
		/**
		 * The tests contained in with this suite
		 *
		 * @var array
		 */
		protected $tests;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $name  Name of the test suite
		 * @param  array $tests Tests contained in this test suite
		 */
		public function __construct($name, array $tests = array()) {
			$this->name  = $name;
			$this->tests = $tests;
		}
		
		/**
		 * Gets the name of this test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return string
		 */
		public final function getName() {
			return $this->name;
		}
		
		/**
		 * Called before the test suite runs
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		protected function setUp() {
		
		}
		
		/**
		 * Called after the test suite runs
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		protected function tearDown() {
		
		}
		
		/**
		 * Adds a test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  Testable $test
		 * @return TestSuite
		 */
		public final function addTest(Testable $test) {
			$this->tests[] = $test;
			return $this;
		}
		
		/**
		 * Gets all tests
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
		 * Runs the test suite
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestListener::beforeTestSuite()
		 * @uses    setUp()
		 * @uses    CombinedTestResult::addTestResult()
		 * @uses    TestListener::onFrameworkWarning()
		 * @uses    tearDown()
		 * @uses    TestListener::afterTestSuite()
		 * 
		 * @param  array $listeners Array of {@link TestListener}s
		 * @return CombinedTestResult
		 */
		public function run(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->beforeTestSuite($this);
			}
			
			$result = new CombinedTestResult();
			
			$this->setUp();
			foreach ($this->tests as $test) {
				if ($test instanceof Testable) {
					$result->addTestResult($test->run($listeners));
				} else {
					foreach ($this->listeners as $listener) {
						$listener->onFrameworkWarning('Unable to run test because it is not an instance of Testable (' . gettype($test) . ')');
					}
				}
			}
			$this->tearDown();
			
			foreach ($listeners as $listener) {
				$listener->afterTestSuite($this);
			}
			
			return $result;
		}
		
		/**
		 * Gets the number of tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return int
		 */
		public function count() {
			return count($this->tests);
		}
		
		/**
		 * Gets the number of test suites, cases and methods
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    Util::countTests()
		 * 
		 * @return array
		 */
		public function getTestCount() {
			return Util::countTests($this->tests);
		}
		
	}

?>