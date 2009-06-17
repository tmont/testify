<?php

	final class MockHandler {
		
		private $className;
		
		public function __construct($mock) {
			$this->className = get_class($mock);
		}
		
		public function expectsMethod($methodName) {
			$expectation = new InvocationExpectation($methodName);
			MockObjectCreator::addExpectation($this->className, $expectation);
			return $expectation;
		}
		
	}

?>