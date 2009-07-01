<?php

	/**
	 * IdenticalConstraint
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Constraint for asserting that two objects are identical
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class IdenticalConstraint extends DefaultConstraint {
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return mixed
		 */
		public function evaluate() {
			return $this->expected === $this->actual;
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
			return Util::export($this->actual) . ' is identical to ' . Util::export($this->expected);
		}
		
	}

?>