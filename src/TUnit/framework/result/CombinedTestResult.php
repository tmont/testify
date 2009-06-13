<?php

	class CombinedTestResult implements TestResult {
		
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
			return count($this->getAllTestResults());
		}
		
		public function addTestResult(TestResult $result) {
			$this->testResults[] = $result;
		}
		
		public function getAllTestResults() {
			//use a recursive iterator here...
			return $this->testResults;
		}
		
		public function getPassedTestResults() {
			//use a recursive iterator here...
			$passedTests = array();
			foreach ($this->testResults as $testResult) {
				if ($testResult instanceof PassedTestResult) {
					$passedTests[] = $testResult;
				}
			}
			
			return $passedTests;
		}
		
		public function getFailedTestResults() {
			//use a recursive iterator here...
			$failedTests = array();
			foreach ($this->testResults as $testResult) {
				if (!($testResult instanceof PassedTestResult)) {
					$failedTests[] = $testResult;
				}
			}
			
			return $failedTests;
		}
		
		public function publish(array $listeners) {
			foreach ($this->getAllTestResults() as $result) {
				foreach ($listeners as $listener) {
					$listener->publishTestResult($result);
				}
			}
		}
		
	}

?>