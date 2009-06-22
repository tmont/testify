<?php

	/**
	 * TestListener
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Base test listener
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class TestListener {
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		public function __construct() {
			
		}
		
		/**
		 * Gets called before the test runner runs the tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestRunner $runner
		 */
		public function beforeTestRunner(TestRunner $runner) {
		
		}
		
		/**
		 * Gets called after the test runner runs the tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestRunner $runner
		 */
		public function afterTestRunner(TestRunner $runner) {
			
		}
		
		/**
		 * Gets called before a test suite is run
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestSuite $suite
		 */
		public function beforeTestSuite(TestSuite $suite) {
		
		}
		
		/**
		 * Gets called after a test suite is run
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestSuite $suite
		 */
		public function afterTestSuite(TestSuite $suite) {
		
		}
		
		/**
		 * Gets called before a test case is run
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestCase $test
		 */
		public function beforeTestCase(TestCase $test) {
		
		}
		
		/**
		 * Gets called after a test case is run
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestCase $test
		 */
		public function afterTestCase(TestCase $test) {
		
		}
		
		/**
		 * Gets called when a test case fails
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestCase $test
		 */
		public function onTestCaseFailed(TestCase $test) {
		
		}
		
		/**
		 * Gets called when a test case passes
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestCase $test
		 */
		public function onTestCasePassed(TestCase $test) {
		
		}
		
		/**
		 * Gets called before a test method is run
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
		public function beforeTestMethod(TestMethod $method) {
		
		}
		
		/**
		 * Gets called after a test method is run
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
		public function afterTestMethod(TestMethod $method) {
		
		}
		
		/**
		 * Gets called when a test method fails
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
		public function onTestMethodFailed(TestMethod $method) {
		
		}
		
		/**
		 * Gets called when a test method passes
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
		public function onTestMethodPassed(TestMethod $method) {
		
		}
		
		/**
		 * Gets called when a test method errs
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
		public function onTestMethodErred(TestMethod $method) {
		
		}
		
		/**
		 * Gets called when a test method is ignored
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestMethod $method
		 */
		public function onTestMethodIgnored(TestMethod $method) {
		
		}
		
		/**
		 * Gets called when the TUnit framework encounters an error
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $message
		 */
		public function onFrameworkError($message) {
		
		}
		
		/**
		 * Gets called when the TUnit framework encounters a warning
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $message
		 */
		public function onFrameworkWarning($message) {
		
		}
		
		/**
		 * Gets called when the test runner publishes the results of a test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  array|TestResult $results
		 */
		public function publishTestResults($results) {
		
		}
		
	}

?>