<?php

	class MockRegistry {
		
		private static $trackers     = array();
		private static $expectations = array();
		
		private function __construct() {}
		
		public static function reset() {
			self::$trackers     = array();
			self::$expectations = array();
		}
		
		public static function addClass($className) {
			self::$trackers[$className]     = new InvocationTracker();
			self::$expectations[$className] = array();
		}
		
		public static function addExpectation($className, InvocationExpectation $expectation) {
			if (!isset(self::$expectations[$className])) {
				throw new LogicException('Unable to add invocation expectation because the object does not exist in the registry');
			}
			
			self::$expectations[$className][] = $expectation;
		}
		
		public static function getAllExpectations() {
			return Util::arrayFlatten(self::$expectations);
		}
		
		public static function getExpectations($className) {
			if (!isset(self::$expectations[$className])) {
				throw new LogicException('Unable to add invocation expectation because the object does not exist in the registry');
			}
			
			return self::$expectations[$className];
		}
		
		public static function registerInvocation($className, $methodName, array $args) {
			if (!isset(self::$trackers[$className])) {
				throw new LogicException('Unable to register invocation because the object does not exist in the registry');
			}
			
			$count = self::getInvocationCount($className, $methodName) + 1;
			$expectation = self::$trackers[$className]->registerInvocation(new MockInvocation($className, $methodName, $args, $count));
			return $expectation;
		}
		
		public static function getTracker($name) {
			if (!isset(self::$trackers[$name])) {
				throw new LogicException('Unable to retrieve invocation tracker because the object does not exist in the registry');
			}
			
			return self::$trackers[$name];
		}
		
		public static function getTrackers() {
			return self::$trackers;
		}
		
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