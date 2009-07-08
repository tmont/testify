<?php

	final class ErrorStack {
		
		private static $errorStack   = array();
		private static $warningStack = array();
		
		private function __construct() {}
		
		private static function addToStack($message, $type = 'error') {
			if ($message instanceof Exception) {
				$message = $e->getMessage() . "\n" . $e->getTraceAsString();
			} else if (!is_string($message)) {
				throw new InvalidArgumentException('1st argument must be a string or an instance of Exception');
			}
			
			if ($type === 'warning') {
				self::$warningStack[] = $message;
			} else {
				self::$errorStack[] = $message;
			}
		}
		
		public static function addError($message) {
			self::addToStack($message, 'error');
		}
		
		public static function addWarning($message) {
			self::addToStack($message, 'warning');
		}
		
		public static function getErrors() {
			return self::$errorStack;
		}
		
		public static function getWarnings() {
			return self::$warningStack;
		}
		
		public static function getAll() {
			return array_merge(self::$errorStack, self::$warningStack);
		}
		
		public static function clear() {
			self::$errorStack   = array();
			self::$warningStack = array();
		}
		
	}

?>