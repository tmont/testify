<?php

	class GreaterThanConstraint extends DefaultConstraint {
		
		public function evaluate() {
			return $this->actual > $this->expected;
		}
		
		protected function getFailureMessage() {
			return $this->actual . ' is greater than ' . $this->expected;
		}
		
	}
	
	class GreaterThanOrEqualToConstraint extends DefaultConstraint {
		
		public function evaluate() {
			return $this->actual >= $this->expected;
		}
		
		protected function getFailureMessage() {
			return $this->actual . ' is greater than or equal to ' . $this->expected;
		}
		
	}
	
	class LessThanConstraint extends DefaultConstraint {
		
		public function evaluate() {
			return $this->actual < $this->expected;
		}
		
		protected function getFailureMessage() {
			return $this->actual . ' is less than ' . $this->expected;
		}
		
	}
	
	class LessThanOrEqualToConstraint extends DefaultConstraint {
		
		public function evaluate() {
			return $this->actual <= $this->expected;
		}
		
		protected function getFailureMessage() {
			return $this->actual . ' is less than or equal to ' . $this->expected;
		}
		
	}
	
?>