<?php

	class EqualConstraint extends DefaultConstraint {
		
		public function evaluate() {
			return $this->expected == $this->actual;
		}
		
		protected function getFailureMessage() {
			return Util::export($this->actual) . ' is equal to ' . Util::export($this->expected);
		}
		
	}

?>