<?php

	class InvocationExpectation {
		
		protected $className;
		protected $method;
		protected $count;
		protected $args;
		protected $returnValue;
		protected $echoString;
		protected $verified;
		
		public function __construct($methodName) {
			$this->method       = $methodName;
			$this->count        = 0;
			$this->args         = array();
			$this->returnValue  = null;
			$this->echoString   = null;
			$this->verified     = false;
		}
		
		public final function getMethod() {
			return $this->method;
		}
		
		public final function getCount() {
			return $this->count;
		}
		
		public final function getArgs() {
			return $this->args;
		}
		
		public function toBeCalled($count) {
			$this->count = $count;
			return $this;
		}
		
		public function toBeCallExactly($count) {
			throw new BadMethodCallException('Not implemented yet');
		}
		
		public function withArguments() {
			$this->args = func_get_args();
			return $this;
		}
		
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
		
		public function andToEcho($value) {
			return $this->toEcho($value);
		}
		
		public function toReturn($value) {
			$this->returnValue = $value;
		}
		
		public function andToReturn($value) {
			$this->toReturn($value);
		}
		
		public function execute() {
			if (!empty($this->echoString)) {
				echo $this->echoString;
			}
			
			return $this->returnValue;
		}
		
		public function matchesInvocation(MockInvocation $invocation) {
			return $this->method === $invocation->getMethod() && $this->countIsAcceptable($invocation->getCount()) && $this->args == $invocation->getArgs();
		}
		
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
		
		public final function isVerified() {
			return $this->verified;
		}
		
		public final function setVerified($verified) {
			$this->verified = (bool)$verified;
		}
		
	}

?>