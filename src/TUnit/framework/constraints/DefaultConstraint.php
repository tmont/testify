<?php

	/**
	 * DefaultConstraint
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Default constraint
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	abstract class DefaultConstraint extends Constraint {
		
		/**
		 * The expected value
		 *
		 * @var mixed
		 */
		protected $expected;
		
		/**
		 * The actual value
		 *
		 * @var mixed
		 */
		protected $actual;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $expected
		 * @param  mixed $actual
		 */
		public function __construct($expected, $actual) {
			$this->expected = $expected;
			$this->actual   = $actual;
		}
		
	}

?>