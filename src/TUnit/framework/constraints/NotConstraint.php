<?php

	class NotConstraint implements Constraint {
		
		protected $constraint;
		
		public function __construct(Constraint $constraint) {
			$this->constraint = $constraint;
		}
		
		public function fail($message = '') {
			throw new FailedTest($this->toString($message));
		}
		
		public function evaluate() {
			return !$this->constraint->evaluate();
		}
		
		public function toString($message) {
			return $this->negateString($constraint->toString($message));
		}
		
		protected function negateString($string) {
			return str_replace(
				array(
					' is '
				),
				array(
					' is not '
				),
				$string
			);
		}
		
	}

?>