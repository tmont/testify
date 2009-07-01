<?php

	/**
	 * CombinedTestResult
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Represents a collection of test results
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class CombinedTestResult implements TestResult {
		
		/**
		 * @var array
		 */
		protected $testResults;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  array $results Array of {@link TestResult}s
		 */
		public function __construct(array $results = array()) {
			$this->testResults = $results;
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getFailedTestResults()
		 * 
		 * @return bool
		 */
		public function passed() {
			return count($this->getFailedTestResults()) === 0;
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getFailedTestResults()
		 * 
		 * @return bool
		 */
		public function failed() {
			return count($this->getFailedTestResults()) > 0;
		}
		
		/**
		 * gets the number of tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getAllTestResults()
		 * 
		 * @return int
		 */
		public function count() {
			return count($this->getAllTestResults());
		}
		
		/**
		 * Adds a test result
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  TestResult $result
		 */
		public final function addTestResult(TestResult $result) {
			$this->testResults[] = $result;
		}
		
		/**
		 * Gets the test results
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return array
		 */
		public final function getTestResults() {
			return $this->testResults;
		}
		
		/**
		 * Flattens out combined results into single test results and
		 * returns a single-dimensional array of all of them
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    RecursiveTestIterator
		 * 
		 * @return array
		 */
		public final function getAllTestResults() {
			$tests = array();
			foreach (new RecursiveIteratorIterator(new RecursiveTestIterator($this->testResults)) as $test) {
				$tests[] = $test;
			}
			
			return $tests;
		}
		
		/**
		 * Gets all passed test results
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getAllTestResults()
		 * 
		 * @return array
		 */
		public function getPassedTestResults() {
			$passedTests = array();
			foreach ($this->getAllTestResults() as $testResult) {
				if ($testResult instanceof PassedTestResult) {
					$passedTests[] = $testResult;
				}
			}
			
			return $passedTests;
		}
		
		/**
		 * Gets all failed test results
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getAllTestResults()
		 * 
		 * @return array
		 */
		public function getFailedTestResults() {
			$failedTests = array();
			foreach ($this->getAllTestResults() as $testResult) {
				if ($testResult instanceof FailedTestResult) {
					$failedTests[] = $testResult;
				}
			}
			
			return $failedTests;
		}
		
		/**
		 * Gets all ignored test results
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getAllTestResults()
		 * 
		 * @return array
		 */
		public function getIgnoredTestResults() {
			$ignoredTests = array();
			foreach ($this->getAllTestResults() as $testResult) {
				if ($testResult instanceof IgnoredTestResult) {
					$ignoredTests[] = $testResult;
				}
			}
			
			return $ignoredTests;
		}
		
		/**
		 * Gets all erred test results
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getAllTestResults()
		 * 
		 * @return array
		 */
		public function getErredTestResults() {
			$erredTests = array();
			foreach ($this->getAllTestResults() as $testResult) {
				if ($testResult instanceof ErredTestResult) {
					$erredTests[] = $testResult;
				}
			}
			
			return $erredTests;
		}
		
	}

?>