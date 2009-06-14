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
		
		public static function identical($expected, $actual, $message = '') {
			self::evaluate(new IdenticalConstraint($expected, $actual), $message);
		}
		
		public static function notIdentical($expected, $actual, $message = '') {
			self::evaluate(self::negate(new IdenticalConstraint($expected, $actual)), $message);
		}
		
		public static function isTrue($value, $message = '') {
			self::evaluate(new TrueConstraint($value), $message);
		}
		
		public static function isFalse($value, $message = '') {
			self::evaluate(new FalseConstraint($value), $message);
		}
		
		public static function set($value, $message = '') {
			self::evaluate(new IssetConstraint($value), $message);
		}
		
		public static function notSet($value, $message = '') {
			self::evaluate(self::negate(new IssetConstraint($value)), $message);
		}
		
		public static function isEmpty($value, $message = '') {
			self::evaluate(new EmptyConstraint($value), $message);
		}
		
		public static function isNotEmpty($value, $message = '') {
			self::evaluate(self::negate(new EmptyConstraint($value)), $message);
		}
		
		public static function isNull($value, $message = '') {
			self::evaluate(new NullConstraint($value), $message);
		}
		
		public static function isNotNull($value, $message = '') {
			self::evaluate(self::negate(new NullConstraint($value)), $message);
		}
		
	}

?>