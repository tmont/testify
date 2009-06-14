<?php

	abstract class Constraint {
		
		public function fail($message = '') {
			throw new FailedTest($this->toString($message));
		}
		
		public function toString($message) {
			$message = !empty($message) ? $message . "\n" : '';
			$message .= 'Failed asserting that ' . $this->getFailureMessage();
			return $message;
		}
		
		public abstract function evaluate();
		
		protected function getFailureMessage() {
			return 'something';
		}
		
	}

?>