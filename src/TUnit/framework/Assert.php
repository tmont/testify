<?php

	class Assert {
		
		private function __construct() {}
		
		protected static function evaluate(Constraint $constraint, $message) {
			if (!$constraint->evaluate()) {
				$constraint->fail($message);
			}
		}
		
		protected static function negate(Constraint $constraint) {
			return new NotConstraint($constraint);
		}
		
		public static function equal($expected, $actual, $message = '') {
			self::evaluate(new EqualConstraint($expected, $actual), $message);
		}
		
		public static function notEqual($expected, $actual, $message = '') {
			self::evaluate(self::negate(new EqualConstraint($expected, $actual)), $message);
		}
		
	}

?>