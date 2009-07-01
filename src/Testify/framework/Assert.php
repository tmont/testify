<?php

	/**
	 * Assert
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Assertions
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class Assert {
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @ignore
		 */
		private function __construct() {}
		
		/**
		 * Evaluates the given constraint
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    Constraint::fail()
		 * 
		 * @param  Constraint $constraint
		 * @param  string     $message
		 */
		protected static function evaluate(Constraint $constraint, $message) {
			if (!$constraint->evaluate()) {
				$constraint->fail($message);
			}
		}
		
		/**
		 * Negates a constraint
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  Constraint $constraint
		 * @return NotConstraint
		 */
		protected static function negate(Constraint $constraint) {
			return new NotConstraint($constraint);
		}
		
		/**
		 * Asserts that two values are equal
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $expected
		 * @param  mixed  $actual
		 * @param  string $message
		 */
		public static function equal($expected, $actual, $message = '') {
			self::evaluate(new EqualConstraint($expected, $actual), $message);
		}
		
		/**
		 * Asserts that two values are not equal
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $expected
		 * @param  mixed  $actual
		 * @param  string $message
		 */
		public static function notEqual($expected, $actual, $message = '') {
			self::evaluate(self::negate(new EqualConstraint($expected, $actual)), $message);
		}
		
		/**
		 * Asserts that two values are identical
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $expected
		 * @param  mixed  $actual
		 * @param  string $message
		 */
		public static function identical($expected, $actual, $message = '') {
			self::evaluate(new IdenticalConstraint($expected, $actual), $message);
		}
		
		/**
		 * Asserts that two values are not identical
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $expected
		 * @param  mixed  $actual
		 * @param  string $message
		 */
		public static function notIdentical($expected, $actual, $message = '') {
			self::evaluate(self::negate(new IdenticalConstraint($expected, $actual)), $message);
		}
		
		/**
		 * Asserts that a value is true
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isTrue($value, $message = '') {
			self::evaluate(new TrueConstraint($value), $message);
		}
		
		/**
		 * Asserts that a value is false
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isFalse($value, $message = '') {
			self::evaluate(new FalseConstraint($value), $message);
		}
		
		/**
		 * Asserts that a value is set
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function set($value, $message = '') {
			self::evaluate(new IssetConstraint($value), $message);
		}
		
		/**
		 * Asserts that a value is not set
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function notSet($value, $message = '') {
			self::evaluate(self::negate(new IssetConstraint($value)), $message);
		}
		
		/**
		 * Asserts that a value is empty
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isEmpty($value, $message = '') {
			self::evaluate(new EmptyConstraint($value), $message);
		}
		
		/**
		 * Asserts that a value is not empty
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotEmpty($value, $message = '') {
			self::evaluate(self::negate(new EmptyConstraint($value)), $message);
		}
		
		/**
		 * Asserts that a value is null
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNull($value, $message = '') {
			self::evaluate(new NullConstraint($value), $message);
		}
		
		/**
		 * Asserts that a value is not null
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotNull($value, $message = '') {
			self::evaluate(self::negate(new NullConstraint($value)), $message);
		}
		
	}

?>