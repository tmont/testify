<?php

	class PassedTestResult implements TestResult {
		
		protected $test;
		
		public function __construct(Testable $test) {
			$this->test = $test;
		}
		
		public function passed() {
			return true;
		}
		
		public function failed() {
			return false;
		}
		
		public function count() {
			return 1;
		}
		
	}

?>