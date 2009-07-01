<?php

	/**
	 * TestResult
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Test result interface
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	interface TestResult extends Countable {
		
		/**
		 * Gets whether the test(s) passed
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		public function passed();
		
		/**
		 * Gets whether the test(s) failed
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		public function failed();
		
	}

?>