<?php

	/**
	 * MockRegistry
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Registry of mock objects
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class MockRegistry {
		
		/**
		 * Array of {@link InvocationTracker}s
		 *
		 * @var array
		 */
		private static $trackers = array();
		
		/**
		 * Array of {@link InvocationExpectation}s
		 *
		 * @var array
		 */
		private static $expectations = array();
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @ignore
		 */
		private function __construct() {}
		
		/**
		 * Resets the registry
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		public static function reset() {
			self::$trackers     = array();
			self::$expectations = array();
		}
		
		/**
		 * Adds a class to the registry and registers a tracker for it
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $className
		 */
		public static function addClass($className) {
			self::$trackers[$className]     = new InvocationTracker();
			self::$expectations[$className] = array();
		}
		
		/**
		 * Adds an expectation for the specified class
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string                $className
		 * @param  InvocationExpectation $expectation
		 * @throws LogicException if the class does not exist in the registry
		 */
		public static function addExpectation($className, InvocationExpectation $expectation) {
			if (!isset(self::$expectations[$className])) {
				throw new LogicException('Unable to add invocation expectation because the object does not exist in the registry');
			}
			
			self::$expectations[$className][] = $expectation;
		}
		
		/**
		 * Gets all registered expectations
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    Util::arrayFlatten()
		 * 
		 * @return array
		 */
		public static function getAllExpectations() {
			return Util::arrayFlatten(self::$expectations);
		}
		
		/**
		 * Gets all registered expectations for the specified class
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $className
		 * @throws LogicException if the class does not exist in the registry
		 * @return array
		 */
		public static function getExpectations($className) {
			if (!isset(self::$expectations[$className])) {
				throw new LogicException('Unable to add invocation expectation because the object does not exist in the registry');
			}
			
			return self::$expectations[$className];
		}
		
		/**
		 * Registers an invocation for the specified class
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getInvocationCount()
		 * @uses    InvocationTracker::registerInvocation()
		 * 
		 * @param  string $className
		 * @param  string $methodName
		 * @param  array  $args
		 * @throws LogicException if the class does not exist in the registry
		 * @return InvocationExpectation|false Returns the matched invocation, or false if no match
		 */
		public static function registerInvocation($className, $methodName, array $args) {
			if (!isset(self::$trackers[$className])) {
				throw new LogicException('Unable to register invocation because the object does not exist in the registry');
			}
			
			$count = self::getInvocationCount($className, $methodName) + 1;
			$expectation = self::$trackers[$className]->registerInvocation(new MockInvocation($className, $methodName, $args, $count));
			return $expectation;
		}
		
		/**
		 * Gets the tracker for the specified class
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $name
		 * @throws LogicException if the class does not exist in the registry
		 * @return InvocationTracker
		 */
		public static function getTracker($name) {
			if (!isset(self::$trackers[$name])) {
				throw new LogicException('Unable to retrieve invocation tracker because the object does not exist in the registry');
			}
			
			return self::$trackers[$name];
		}
		
		/**
		 * Gets all registered {@link InvocationTracker}s
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return array
		 */
		public static function getTrackers() {
			return self::$trackers;
		}
		
		/**
		 * Gets the current invocation count for the class and method
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    InvocationTracker::getInvocations()
		 * @uses    MockInvocation::getMethod()
		 * 
		 * @param  string $className
		 * @param  string $methodName
		 * @return int
		 */
		private static function getInvocationCount($className, $methodName) {
			$count = 0;
			foreach (self::$trackers[$className]->getInvocations() as $invocation) {
				if ($invocation->getMethod() === $methodName) {
					$count++;
				}
			}
			
			return $count;
		}
		
	}

?>