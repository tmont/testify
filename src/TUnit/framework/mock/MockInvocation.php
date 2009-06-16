<?php

	abstract class MockInvocation implements Verifiable {
		
		protected $method;
		protected $args;
		
		public function __construct($methodName, array $args) {
			$this->method = $methodName;
			$this->args   = $args;
		}
		
		public function getMethod() {
			return $this->method;
		}
		
		public function getArgs() {
			return $this->args;
		}
		
	}

?>