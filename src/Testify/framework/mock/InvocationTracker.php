<?php

	/**
	 * InvocationTracker
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Tracks mock invocations
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class InvocationTracker {
		
		/**
		 * @var array
		 */
		protected $invocations;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		public function __construct() {
			$this->invocations = array();
		}
		
		/**
		 * Registers a invocation
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    MockRegistry::getExpectations()
		 * @uses    MockInvocation::getClass()
		 * @uses    InvocationExpectation::matchsInvocation()
		 * @uses    InvocationExpectation::setVerified()
		 * 
		 * @param  MockInvocation $invocation
		 * @return InvocationExpectation|bool Returns the matching InvocationExpectation if there is one, or false if not
		 */
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
		
		/**
		 * Gets
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return mixed
		 */
		public final function getInvocations() {
			return $this->invocations;
		}
		
		/**
		 * Verifies all invocations
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    MockRegistry::getAllExpectations()
		 * @uses    InvocationExpectation::isVerified()
		 * 
		 * @return bool true if all invocation expectations have been verified, false if not
		 */
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