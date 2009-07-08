<?php

	class ArrayHasKeyConstraint extends DefaultConstraint {
		
		public function __construct($key, array $array, $message = '') {
			parent::__construct($key, $array, $message);
		}
		
		public function evaluate() {
			return array_key_exists($this->expected, $this->actual);
		}
		
		protected function getFailureMessage() {
			return Util::export($this->actual) . " has key \"$this->expected\"";
		}
		
	}
	
	class ArrayHasValueConstraint extends DefaultConstraint {
		
		public function __construct($value, array $array, $message = '') {
			parent::__construct($value, $array, $message);
		}
		
		public function evaluate() {
			return in_array($this->expected, $this->actual);
		}
		
		protected function getFailureMessage() {
			return 'the value ' . Util::export($this->expected) . ' is in the array ' . Util::export($this->actual);
		}
		
	}

?>