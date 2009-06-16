<?php

	class InvocationTracker {
		
		protected $invocations;
		
		public function __construct() {
			$this->invocations = array();
		}
		
		public function registerInvocation(MockInvocation $invocation) {
			$this->invocations[] = $invocation;
		}
		
	}

?>