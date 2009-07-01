<?php

	/**
	 * InvocationExpectation
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Represents what is expected of a mock object's method
	 * invocation
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class InvocationExpectation {
		
		/**
		 * The method of the mock invocation
		 *
		 * @var string
		 */
		protected $method;
		
		/**
		 * The number of times the method is expected to be invoked
		 *
		 * @var int
		 */
		protected $count;
		
		/**
		 * The arguments the method should be invoked with
		 *
		 * @var array
		 */
		protected $args;
		
		/**
		 * The value to return after invocation
		 *
		 * @var mixed
		 */
		protected $returnValue;
		
		/**
		 * String to echo after invocation
		 *
		 * @var mixed
		 */
		protected $echoString;
		
		/**
		 * Whether the expectation has been verified
		 *
		 * @var bool
		 */
		protected $verified;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  sttring $methodName
		 */
		public function __construct($methodName) {
			$this->method      = $methodName;
			$this->count       = 0;
			$this->args        = array();
			$this->returnValue = null;
			$this->echoString  = null;
			$this->verified    = false;
		}
		
		/**
		 * Gets the method name
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return string
		 */
		public final function getMethod() {
			return $this->method;
		}
		
		/**
		 * Gets the count
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return int
		 */
		public final function getCount() {
			return $this->count;
		}
		
		/**
		 * Gets the expected arguments
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return array
		 */
		public final function getArgs() {
			return $this->args;
		}
		
		/**
		 * The number of times you expect the method to be called
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  int $count
		 * @return InvocationExpectation
		 */
		public function toBeCalled($count) {
			$this->count = $count;
			return $this;
		}
		
		/**
		 * The arguments you expect the method to be called with
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return InvocationExpectation
		 */
		public function withArguments() {
			$this->args = func_get_args();
			return $this;
		}
		
		/**
		 * The string you want the method to echo
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $value Primitive or __toString()-able object
		 * @throws InvalidArgumentException
		 * @return InvocationExpectation
		 */
		public function toEcho($value) {
			if (!is_scalar($value)) {
				if (is_object($value)) {
					$refClass = new ReflectionClass($value);
					if ($refClass->hasMethod('__toString')) {
						$value = $refClass->getMethod('__toString')->invoke($value);
					} else {
						throw new InvalidArgumentException('1st argument must be a scalar value or a __toString()-able object');
					}
				} else {
					throw new InvalidArgumentException('1st argument must be a scalar value or a __toString()-able object');
				}
			}
			
			$this->echoString = $value;
			return $this;
		}
		
		/**
		 * The string you want the method to echo
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    toEcho()
		 * 
		 * @param  mixed $value
		 * @return InvocationExpectation
		 */
		public function andToEcho($value) {
			return $this->toEcho($value);
		}
		
		/**
		 * The value you want the method to return
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $value
		 */
		public function toReturn($value) {
			$this->returnValue = $value;
		}
		
		/**
		 * The value you want the method to return
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    toReturn()
		 * 
		 * @param  mixed $value
		 */
		public function andToReturn($value) {
			$this->toReturn($value);
		}
		
		/**
		 * Executes the invocation expectation
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return mixed The return value given by {@link toReturn()}
		 */
		public function execute() {
			if (!empty($this->echoString)) {
				echo $this->echoString;
			}
			
			return $this->returnValue;
		}
		
		/**
		 * Determines whether this expectation matches the given invocation
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    MockInvocation::getMethod()
		 * @uses    MockInvocation::getCount()
		 * @uses    MockInvocation::getArgs()
		 * @uses    countIsAcceptable()
		 * 
		 * @param  MockInvocation $invocation
		 * @return bool
		 */
		public function matchesInvocation(MockInvocation $invocation) {
			return $this->method === $invocation->getMethod() && $this->countIsAcceptable($invocation->getCount()) && $this->args == $invocation->getArgs();
		}
		
		/**
		 * Determines if the given count is acceptable to this expectation
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  int $count
		 * @return bool
		 */
		protected function countIsAcceptable($count) {
			switch ($this->count) {
				case TestCase::ANY:
					return true;
				case TestCase::AT_LEAST_ONCE:
					return $count > 0;
				default:
					return $count === $this->count;
			}
		}
		
		/**
		 * Gets whether this expectation has been verified
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public final function isVerified() {
			return $this->verified;
		}
		
		/**
		 * Sets whether this expectation has been verified
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  bool $verified
		 */
		public final function setVerified($verified) {
			$this->verified = (bool)$verified;
		}
		
	}

?>