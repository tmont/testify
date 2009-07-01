<?php

	/**
	 * Constraints that only take one value
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Base class for simple constraints that only require one value
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	*/
	abstract class SimpleConstraint extends Constraint {
		
		/**
		 * The value to evaluate
		 *
		 * @var mixed
		 */
		protected $value;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $value
		 */
		public function __construct($value) {
			$this->value = $value;
		}
		
	}

	/**
	 * Constraint for asserting that a value is set
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class IssetConstraint extends SimpleConstraint {
		
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
			return isset($this->value);
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
			return Util::export($this->value) . ' is set';
		}
		
	}
	
	/**
	 * Constraint for asserting that a value is empty
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class EmptyConstraint extends SimpleConstraint {
		
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
			return empty($this->value);
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
			return Util::export($this->value) . ' is empty';
		}
		
	}
	
	/**
	 * Constraint for asserting that a value is null
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class NullConstraint extends SimpleConstraint {
		
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
			return $this->value === null;
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
			return Util::export($this->value) . ' is null';
		}
		
	}
	
	/**
	 * Constraint for asserting that a value is true
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class TrueConstraint extends SimpleConstraint {
		
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
			return $this->value === true;
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
			return Util::export($this->value) . ' is true';
		}
		
	}
	
	/**
	 * Constraint for asserting that a value is true
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class FalseConstraint extends SimpleConstraint {
		
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
			return $this->value === false;
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
			return Util::export($this->value) . ' is false';
		}
		
	}

?>