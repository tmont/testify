<?php

	/**
	 * Contains all custom exception classes
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Base exception class for Testify
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class TestifyException extends Exception {}

	/**
	 * Exception for invalid test runner options
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 * @see     TestRunner::parseOptions()
	 */
	class InvalidOptionException extends TestifyException {
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $switch  The invalid switch
		 * @param  string $message
		 */
		public function __construct($switch, $message = '') {
			if (!empty($message)) {
				$message = ': ' . $message;
			}
			
			$message = 'Invalid option (' . $option . ')' . $message;
			parent::__construct($message);
		}
		
	}

?>