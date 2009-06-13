<?php

	class Assert {
		
		private function __construct() {}
		
		protected static function evaluate(Constraint $constraint, $message) {
			if (!$constraint->evaluate()) {
				$constraint->fail($message);
			}
		}
		
		public static function equals($expected, $actual, $message = '') {
			self::evaluate(new EqualsConstraint($expected, $actual), $message);
		}
		
	}

?>