<?php

	final class CoverageFilter {
		
		private static $files        = array();
		private static $directories  = array();
		
		private function __construct() {}
		
		public static function addFile($file) {
			self::$files[] = $file;
		}
		
		public static function addDirectory($dir) {
			self::$directories[] = $dir;
		}
		
		public static function clear() {
			self::$files        = array();
			self::$directories  = array();
		}
		
		public static function getDirectories() {
			return self::$dirs;
		}
		
		public static function getFiles() {
			return self::$files;
		}
		
		public static function addDefaultFilters() {
			self::$directories[] = dirname(dirname(dirname(__FILE__)));
		}
		
		public static function filter(array $data) {
			foreach ($data as $file => $arr) {
				if (strpos($file, ' : runtime-created function') !== false) {
					unset($data[$file]);
				} else if (in_array($file, self::$files)) {
					unset($data[$file]);
				} else {
					foreach (self::$directories as $dir) {
						if (strpos($file, $dir) === 0) {
							unset($data[$file]);
							break;
						}
					}
				}
			}
			
			return $data;
		}
		
	}

?>