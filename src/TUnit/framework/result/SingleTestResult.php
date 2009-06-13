<?php

	abstract class SingleTestResult implements TestResult {
		
		protected $test;
		protected $failure;
		
		public function __construct(Testable $test, TestFailure $failure) {
			$this->test    = $test;
			$this->failure = $failure;
		}
		
		public function count() {
			return 1;
		}
		
		public function publish(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->publishTestResult($this);
			}
		}
		
	}

?>