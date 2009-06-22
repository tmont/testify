<?php

	/**
	 * Contains all custom exception classes
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Base exception class for TUnit
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class TUnitException extends Exception {}

	/**
	 * Exception for invalid test runner options
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 * @see     TestRunner::parseOptions()
	 */
	class InvalidOptionException extends TUnitException {
		
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