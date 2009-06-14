<?php

	abstract class DefaultConstraint implements Constraint {
		
		protected $expected;
		protected $actual;
		
		public function __construct($expected, $actual) {
			$this->expected = $expected;
			$this->actual   = $actual;
		}
		
		public function fail($message = '') {
			throw new FailedTest($this->toString($message));
		}
		
		public function toString($message) {
			$message = !empty($message) ? $message . "\n" : '';
			$message .= "Failed asserting that\n" . $this->getFailureMessage();
			return $message;
		}
		
		protected abstract function getFailureMessage();
		
	}

?>