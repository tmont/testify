<?php

	class EqualConstraint extends DefaultConstraint {
		
		public function evaluate() {
			return $this->expected == $this->actual;
		}
		
		protected function getFailureMessage() {
			return Util::export($this->actual) . "\nis equal to\n" . Util::export($this->expected);
		}
		
	}

?>