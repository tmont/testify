<?php

	/**
	 * Interfaces for tests
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Interface for recursively counting tests
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	interface RecursivelyCountable extends Countable {
		
		/**
		 * Describe this function
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 *
		 * @return array
		 */
		public function getTestCount();
		
	}

	/**
	 * This interface makes a class testable by the Testify framework
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	interface Testable extends RecursivelyCountable {
		
		/**
		 * Runs the test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  array $listeners Array of {@link TestListener}s
		 */
		public function run(array $listeners);
		
		/**
		 * Gets the name of the test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 *
		 * @return string
		 */
		public function getName();
		
	}

?>