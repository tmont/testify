<?php

	/**
	 * MockHandler
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Simple wrapper for mock objects so we don't pollute
	 * objects with member variables
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	final class MockHandler {
		
		/**
		 * @var string
		 */
		private $className;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  object $mock The mock object
		 */
		public function __construct($mock) {
			$this->className = get_class($mock);
		}
		
		/**
		 * The method the mock object expects to call
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    MockRegistry::addExpectation()
		 * 
		 * @param  string $methodName The method that is expected to be invoked
		 * @return InvocationExpectation
		 */
		public function expectsMethod($methodName) {
			$expectation = new InvocationExpectation($methodName);
			MockRegistry::addExpectation($this->className, $expectation);
			return $expectation;
		}
		
	}

?>