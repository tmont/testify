<?php

	class TestCaseResult implements TestResult {
		
		protected $testResults;
		
		public function __construct() {
			$this->testResults = array();
		}
		
		public function passed() {
			return count($this->getFailedTestResults()) === 0;
		}
		
		public function failed() {
			return count($this->getFailedTestResults()) > 0;
		}
		
		public function count() {
			return count($this->testResults);
		}
		
		public function addTestResult(TestResult $result) {
			$this->testResults[] = $result;
		}
		
		public function getTestResults() {
			return $this->testResults;
		}
		
		public function getPassedTestResults() {
			$passedTests = array();
			foreach ($this->testResults as $testResult) {
				if ($testResult instanceof PassedTestResult) {
					$passedTests[] = $testResult;
				}
			}
			
			return $passedTests;
		}
		
		public function getFailedTestResults() {
			$failedTests = array();
			foreach ($this->testResults as $testResult) {
				if (!($testResult instanceof PassedTestResult)) {
					$failedTests[] = $testResult;
				}
			}
			
			return $failedTests;
		}
		
	}

?>