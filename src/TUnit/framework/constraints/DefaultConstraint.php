<?php

	abstract class DefaultConstraint extends Constraint {
		
		protected $expected;
		protected $actual;
		
		public function __construct($expected, $actual) {
			$this->expected = $expected;
			$this->actual   = $actual;
		}
		
	}

?>