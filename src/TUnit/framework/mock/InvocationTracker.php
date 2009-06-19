<?php

	class InvocationTracker {
		
		protected $invocations;
		
		public function __construct() {
			$this->invocations = array();
		}
		
		public function registerInvocation(MockInvocation $invocation) {
			$this->invocations[] = $invocation;
			
			foreach (MockRegistry::getExpectations($invocation->getClass()) as $expectation) {
				if ($expectation->matchesInvocation($invocation)) {
					$expectation->setVerified(true);
					return $expectation;
				}
			}
			
			return false;
		}
		
		public function getInvocations() {
			return $this->invocations;
		}
		
		public function verify() {
			foreach (MockRegistry::getAllExpectations() as $expectation) {
				if (!$expectation->isVerified()) {
					return false;
				}
			}
			
			return true;
		}
		
	}

?>