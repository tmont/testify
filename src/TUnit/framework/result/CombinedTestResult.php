<?php

	class CombinedTestResult implements TestResult {
		
		protected $testResults;
		
		public function __construct(array $results = array()) {
			$this->testResults = $results;
		}
		
		public function passed() {
			return count($this->getFailedTestResults()) === 0;
		}
		
		public function failed() {
			return count($this->getFailedTestResults()) > 0;
		}
		
		public function count() {
			return count($this->getAllTestResults());
		}
		
		public function addTestResult(TestResult $result) {
			$this->testResults[] = $result;
		}
		
		public function getTestResults() {
			return $this->testResults;
		}
		
		public function getAllTestResults() {
			$tests = array();
			foreach (new RecursiveIteratorIterator(new RecursiveTestIterator($this->testResults)) as $test) {
				$tests[] = $test;
			}
			return $tests;
		}
		
		public function getPassedTestResults() {
			$passedTests = array();
			foreach ($this->getAllTestResults() as $testResult) {
				if ($testResult instanceof PassedTestResult) {
					$passedTests[] = $testResult;
				}
			}
			
			return $passedTests;
		}
		
		public function getFailedTestResults() {
			$failedTests = array();
			foreach ($this->getAllTestResults() as $testResult) {
				if ($testResult instanceof FailedTestResult) {
					$failedTests[] = $testResult;
				}
			}
			
			return $failedTests;
		}
		
		public function getIgnoredTestResults() {
			$failedTests = array();
			foreach ($this->getAllTestResults() as $testResult) {
				if ($testResult instanceof IgnoredTestResult) {
					$failedTests[] = $testResult;
				}
			}
			
			return $failedTests;
		}
		
		public function getErredTestResults() {
			$failedTests = array();
			foreach ($this->getAllTestResults() as $testResult) {
				if ($testResult instanceof ErredTestResult) {
					$failedTests[] = $testResult;
				}
			}
			
			return $failedTests;
		}
		
	}

?>