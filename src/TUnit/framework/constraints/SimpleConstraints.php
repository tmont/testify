<?php

	abstract class SimpleConstraint extends Constraint {
		
		protected $value;
		
		public function __construct($value) {
			$this->value = $value;
		}
		
	}

	class IssetConstraint extends SimpleConstraint {
		
		public function evaluate() {
			return isset($this->value);
		}
		
		protected function getFailureMessage() {
			return Util::export($this->value) . ' is set';
		}
		
	}
	
	class EmptyConstraint extends SimpleConstraint {
		
		public function evaluate() {
			return empty($this->value);
		}
		
		protected function getFailureMessage() {
			return Util::export($this->value) . ' is empty';
		}
		
	}
	
	class NullConstraint extends SimpleConstraint {
		
		public function evaluate() {
			return $this->value === null;
		}
		
		protected function getFailureMessage() {
			return Util::export($this->value) . ' is null';
		}
		
	}
	
	class TrueConstraint extends SimpleConstraint {
		
		public function evaluate() {
			return $this->value === true;
		}
		
		protected function getFailureMessage() {
			return Util::export($this->value) . ' is true';
		}
		
	}
	
	class FalseConstraint extends SimpleConstraint {
		
		public function evaluate() {
			return $this->value === false;
		}
		
		protected function getFailureMessage() {
			return Util::export($this->value) . ' is false';
		}
		
	}

?>