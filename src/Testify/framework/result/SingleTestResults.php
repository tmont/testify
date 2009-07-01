<?php

	/**
	 * Single test results
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Represents a single test result
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	abstract class SingleTestResult implements TestResult {
		
		/**
		 * @var Testable
		 */
		protected $test;
		
		/**
		 * @var TestFailure|null
		 */
		protected $failure;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  Testable    $test
		 * @param  TestFailure $failure
		 */
		public function __construct(Testable $test, TestFailure $failure = null) {
			$this->test    = $test;
			$this->failure = $failure;
		}
		
		/**
		 * Gets the test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return Testable
		 */
		public function getTest() {
			return $this->test;
		}
		
		/**
		 * Gets the failure, or null if the test did not fail
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return TestFailure|null
		 */
		public function getFailure() {
			return $this->failure;
		}
		
		/**
		 * Gets the number of tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return int Always returns one
		 */
		public function count() {
			return 1;
		}
		
	}
	
	/**
	 * The test erred
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class ErredTestResult extends SingleTestResult {
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function passed() {
			return false;
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function failed() {
			return true;
		}
		
	}
	
	/**
	 * The test passed
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class PassedTestResult extends SingleTestResult {
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function passed() {
			return true;
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function failed() {
			return false;
		}
		
	}
	
	/**
	 * The test was ignored
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class IgnoredTestResult extends SingleTestResult {
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function passed() {
			return false;
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function failed() {
			return false;
		}
		
	}
	
	/**
	 * The test failed
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class FailedTestResult extends SingleTestResult {
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function passed() {
			return false;
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function failed() {
			return true;
		}
		
	}
	

?>