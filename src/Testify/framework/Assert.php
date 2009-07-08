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
		
		/**
		 * Asserts that a value is a file
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isFile($value, $message = '') {
			self::evaluate(new IsFileConstraint($value), $message);
		}
		
		/**
		 * Asserts that a value is not a file
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotFile($value, $message = '') {
			self::evaluate(self::negate(new IsFileConstraint($value)), $message);
		}
		
		/**
		 * Asserts that a value is a directory
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isDirectory($value, $message = '') {
			self::evaluate(new IsDirectoryConstraint($value), $message);
		}
		
		/**
		 * Asserts that a value is not a directory
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotDirectory($value, $message = '') {
			self::evaluate(self::negate(new IsDirectoryConstraint($value)), $message);
		}
		
		/**
		 * Asserts that a value is an integer
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isInt($value, $message = '') {
			self::evaluate(new TypeConstraint('int', $value), $message);
		}
		
		/**
		 * Asserts that a value is not an integer
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotInt($value, $message = '') {
			self::evaluate(self::negate(new TypeConstraint('int', $value)), $message);
		}
		
		/**
		 * Asserts that a value is a boolean
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isBool($value, $message = '') {
			self::evaluate(new TypeConstraint('bool', $value), $message);
		}
		
		/**
		 * Asserts that a value is not a boolean
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotBool($value, $message = '') {
			self::evaluate(self::negate(new TypeConstraint('bool', $value)), $message);
		}
		
		/**
		 * Asserts that a value is a float
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isFloat($value, $message = '') {
			self::evaluate(new TypeConstraint('float', $value), $message);
		}
		
		/**
		 * Asserts that a value is not a float
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotFloat($value, $message = '') {
			self::evaluate(self::negate(new TypeConstraint('float', $value)), $message);
		}
		
		/**
		 * Asserts that a value is an array
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isArray($value, $message = '') {
			self::evaluate(new TypeConstraint('array', $value), $message);
		}
		
		/**
		 * Asserts that a value is not an array
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotArray($value, $message = '') {
			self::evaluate(self::negate(new TypeConstraint('array', $value)), $message);
		}
		
		/**
		 * Asserts that a value is a string
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isString($value, $message = '') {
			self::evaluate(new TypeConstraint('string', $value), $message);
		}
		
		/**
		 * Asserts that a value is not a string
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function isNotString($value, $message = '') {
			self::evaluate(self::negate(new TypeConstraint('string', $value)), $message);
		}
		
		/**
		 * Asserts that a value is numeric
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function numeric($value, $message = '') {
			self::evaluate(new TypeConstraint('numeric', $value), $message);
		}
		
		/**
		 * Asserts that a value is not numeric
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function notNumeric($value, $message = '') {
			self::evaluate(self::negate(new TypeConstraint('numeric', $value)), $message);
		}
		
		/**
		 * Asserts that a value is greater than an expected value
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $expected
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function greaterThan($expected, $actual, $message = '') {
			self::evaluate(new GreaterThanConstraint($expected, $actual), $message);
		}
		
		/**
		 * Asserts that a value is greater than or equal to an expected value
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $expected
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function greaterThanOrEqualTo($expected, $actual, $message = '') {
			self::evaluate(new GreaterThanOrEqualToConstraint($expected, $actual), $message);
		}
		
		/**
		 * Asserts that a value is less than an expected value
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $expected
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function lessThan($expected, $actual, $message = '') {
			self::evaluate(new LessThanConstraint($expected, $actual), $message);
		}
		
		/**
		 * Asserts that a value is less than or equal to an expected value
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $expected
		 * @param  mixed  $value
		 * @param  string $message
		 */
		public static function lessThanOrEqualTo($expected, $actual, $message = '') {
			self::evaluate(new LessThanOrEqualToConstraint($expected, $actual), $message);
		}
		
		/**
		 * Asserts that a key exists in an array
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $key
		 * @param  array  $array
		 * @param  string $message
		 */
		public static function arrayHasKey($key, array $array, $message = '') {
			self::evaluate(new ArrayHasKeyConstraint($key, $array), $message);
		}
		
		/**
		 * Asserts that a key does not exist in an array
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $key
		 * @param  array  $array
		 * @param  string $message
		 */
		public static function arrayNotHasKey($key, array $array, $message = '') {
			self::evaluate(self::negate(new ArrayHasKeyConstraint($key, $array)), $message);
		}
		
		/**
		 * Asserts that an array contains a value
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  array  $array
		 * @param  string $message
		 */
		public static function arrayHasValue($value, array $array, $message = '') {
			self::evaluate(new ArrayHasValueConstraint($value, $array), $message);
		}
		
		/**
		 * Asserts that an array does not contain a value
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed  $value
		 * @param  array  $array
		 * @param  string $message
		 */
		public static function arrayNotHasValue($value, array $array, $message = '') {
			self::evaluate(self::negate(new ArrayHasValueConstraint($value, $array)), $message);
		}
		
	}

?>