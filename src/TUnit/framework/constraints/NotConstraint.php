<?php

	/**
	 * NotConstraint
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Decorator for negating a constraint
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class NotConstraint extends Constraint {
		
		/**
		 * The constraint to negate
		 *
		 * @var mixed
		 */
		protected $constraint;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  Constraint $constraint The constraint to negate
		 */
		public function __construct(Constraint $constraint) {
			$this->constraint = $constraint;
		}
		
		/**
		 * {@inheritdoc}
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
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function evaluate() {
			return !$this->constraint->evaluate();
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    negateString()
		 * @uses    Constraint::toString()
		 * 
		 * @param  string $message
		 * @return string
		 */
		public function toString($message) {
			return $this->negateString($this->constraint->toString($message));
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @throws BadMethodCallException
		 */
		protected function getFailureMessage() {
			throw new BadMethodCallException();
		}
		
		/**
		 * Negates a string
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $string
		 * @return string
		 */
		protected function negateString($string) {
			return str_replace(
				array(
					' is '
				),
				array(
					' is not '
				),
				$string
			);
		}
		
	}

?>