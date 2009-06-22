<?php
	
	/**
	 * Autoloader
	  *
	 * @package TUnit
	 * @version 1.0
	 * @since   1.0
	 */
	
	/**
	 * Bootstraps each NowhereConcave package via autoload
	 *
	 * @package TUnit
	 * @version 1.0
	 * @since   1.0
	 */
	final class Autoloader {
		
		/**
		 * @var array
		 */
		private static $classMap = array();
		
		//@codeCoverageIgnoreStart
		/**
		 * Not instantiable
		 * @ignore
		 */
		private function __construct() {
		
		}
		//@codeCoverageIgnoreEnd
		
		/**
		 * Gets the current class -> path map
		 *
		 * @version 1.0
		 * @since   1.0
		 *
		 * @return array
		 */
		public static function getClassMap() {
			return self::$classMap;
		}
		
		/**
		 * Loads a class map from an array
		 *
		 * @version 1.0
		 * @since   1.0
		 * @see     loadClassMapFromFile()
		 *
		 * @param  array  $classMap Array of classes and the paths they map to
		 * @param  string $override Whether to override previously existing classes
		 */
		public static function loadClassMap(array $classMap, $override = true) {
			if ((bool)$override) {
				self::$classMap = array_merge(self::$classMap, $classMap);
			} else {
				self::$classMap += $classMap;
			}
		}
		
		/**
		 * Loads a class map from a file
		 *
		 * @version 1.0
		 * @since   1.0
		 * @uses    loadClassMap()
		 *
		 * @param  string $file     The manifest file to load
		 * @param  string $override Whether to override previously existing classes
		 * @throws InvalidArgumentException
		 */
		public static function loadClassMapFromFile($file, $override = true) {
			if (!is_string($file) || !is_file($file)) {
				throw new InvalidArgumentException('1st argument must be an existing file');
			}
			
			self::loadClassMap((array)include $file, $override);
		}
		
		/**
		 * Autoload function used by spl_autoload_call()
		 * @ignore
		 */
		public static function autoload($className) {
			if (isset(self::$classMap[$className])) {
				require_once self::$classMap[$className];
			}
		}
		
	}

?>