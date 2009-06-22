<?php

	/**
	 * Constraint
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Base constraint
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	*/
	abstract class Constraint {
		
		/**
		 * Fails the constraint
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $message
		 * @throws {@link FailedTest}
		 */
		public function fail($message = '') {
			throw new FailedTest($this->toString($message));
		}
		
		/**
		 * Gets a string representation of the constraint
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getFailureMessage()
		 * 
		 * @param  string $message
		 * @return string
		 */
		public function toString($message) {
			$message = !empty($message) ? $message . "\n" : '';
			$message .= 'Failed asserting that ' . $this->getFailureMessage();
			return $message;
		}
		
		/**
		 * Evaluates the constraint
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 *
		 * @return bool
		 */
		public abstract function evaluate();
		
		/**
		 * Gets the failure message
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 *
		 * @return string
		 */
		protected abstract function getFailureMessage();
		
	}

?>