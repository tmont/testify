<?php

	class MockInvocation {
		
		protected $className;
		protected $method;
		protected $args;
		protected $count;
		
		public function __construct($className, $methodName, array $args, $count) {
			$this->className = $className;
			$this->method    = $methodName;
			$this->args      = $args;
			$this->count     = $count;
		}
		
		public function getMethod() {
			return $this->method;
		}
		
		public function getArgs() {
			return $this->args;
		}
		
		public function getClass() {
			return $this->className;
		}
		
		public function getCount() {
			return $this->count;
		}
		
	}

?>