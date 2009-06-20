<?php

	class TUnitException extends Exception {}

	class InvalidOptionException extends TUnitException {
		
		public function __construct($switch, $message = '') {
			if (!empty($message)) {
				$message = ': ' . $message;
			}
			
			$message = 'Invalid option (' . $option . ')' . $message;
			parent::__construct($message);
		}
		
	}

?>