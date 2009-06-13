<?php

	class FailedTestResult implements TestResult {
		
		protected $test;
		protected $failure;
		
		public function __construct(Testable $test, TestFailure $failure) {
			$this->test    = $test;
			$this->failure = $failure;
		}
		
		public function passed() {
			return false;
		}
		
		public function failed() {
			return true;
		}
		
		public function count() {
			return 1;
		}
		
	}

?>