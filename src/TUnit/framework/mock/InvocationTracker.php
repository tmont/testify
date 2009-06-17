<?php

	class InvocationTracker {
		
		protected $invocations;
		
		public function __construct() {
			$this->invocations = array();
		}
		
		public function registerInvocation(MockInvocation $invocation) {
			$this->invocations[] = $invocation;
			
			foreach (MockObjectCreator::getExpectations($invocation->getClass()) as $expectation) {
				if ($expectation->matchesInvocation($invocation)) {
					return $expectation;
				}
			}
			
			return false;
		}
		
		public function getInvocations() {
			return $this->invocations;
		}
		
	}

?>