<?php

	class IdenticalConstraint extends DefaultConstraint {
		
		public function evaluate() {
			return $this->expected === $this->actual;
		}
		
		protected function getFailureMessage() {
			return Util::export($this->actual) . ' is identical to ' . Util::export($this->expected);
		}
		
	}

?>