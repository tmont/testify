<?php

	/**
	 * EqualConstraint
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Constraint for asserting that two objects are equal
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class EqualConstraint extends DefaultConstraint {
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function evaluate() {
			return $this->expected == $this->actual;
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return string
		 */
		protected function getFailureMessage() {
			return Util::export($this->actual) . ' is equal to ' . Util::export($this->expected);
		}
		
	}

?>