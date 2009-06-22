<?php

	/**
	 * RecursiveTestIterator
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Class for recursively iterating over test results
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class RecursiveTestIterator extends ArrayIterator implements RecursiveIterator {
		
		/**
		 * Gets the children of the current iterator
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return self
		 */
		public function getChildren() {
			return new self($this->current()->getTestResults());
		}
		
		/**
		 * Gets whether the current iterator has any children
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return mixed
		 */
		public function hasChildren() {
			return $this->current() instanceof CombinedTestResult;
		}
		
	}

?>