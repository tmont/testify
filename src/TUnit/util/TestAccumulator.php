<?php

	/**
	 * TestAccumulator
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Accumulates test
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class TestAccumulator {
		
		/**
		 * Gets all tests in the specified paths
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getTestsFromDir()
		 * @uses    getTestsFromFile()
		 * 
		 * @param  array $paths
		 * @param  bool  $recursive
		 * @return array An array of {@link Testable}s
		 */
		public static function getTests(array $paths, $recursive = true) {
			$tests = array();
			
			foreach ($paths as $path) {
				$path = realpath($path);
				if (is_dir($path)) {
					$tests = array_merge($tests, self::getTestsFromDir($path, $recursive));
				} else if (is_file($path)) {
					$tests = array_merge($tests, self::getTestsFromFile($path));
				}
			}
			
			return $tests;
		}
		
		/**
		 * Gets all tests in the specified directory
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getTestsFromFile()
		 * 
		 * @param  mixed $dir
		 * @param  bool  $recursive
		 * @return array
		 */
		public static function getTestsFromDir($dir, $recursive = true) {
			$iterator = ($recursive) ? new RecursivePhpFileIterator($dir) : new PhpFileIterator($dir);
			
			$tests = array();
			foreach ($iterator as $file) {
				$temp = self::getTestsFromFile($file->getPathName());
				if (!empty($temp)) {
					$tests = array_merge($tests, $temp);
				}
			}
			
			return $tests;
		}
		
		/**
		 * Gets all tests from the specified file
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $file
		 * @return array
		 */
		public static function getTestsFromFile($file) {
			$tests = array();
			
			$tokens = token_get_all(file_get_contents($file));
			for ($i = 0, $len = count($tokens); $i < $len; $i++) {
				if (
					$tokens[$i][0] === T_CLASS && !(
						isset($tokens[$i - 1]) && is_array($tokens[$i - 1]) && $tokens[$i - 1][1] === T_WHITESPACE &&
						isset($tokens[$i - 2]) && is_array($tokens[$i - 2]) && $tokens[$i - 2][1] === T_ABSTRACT
					)
				) {
					if (
						isset($tokens[$i + 1]) && is_array($tokens[$i + 1]) && $tokens[$i + 1][0] === T_WHITESPACE &&
						isset($tokens[$i + 2]) && is_array($tokens[$i + 2]) && $tokens[$i + 2][0] === T_STRING     &&
						isset($tokens[$i + 3]) && is_array($tokens[$i + 3]) && $tokens[$i + 3][0] === T_WHITESPACE &&
						isset($tokens[$i + 4]) && is_array($tokens[$i + 4]) && $tokens[$i + 4][0] === T_EXTENDS    &&
						isset($tokens[$i + 5]) && is_array($tokens[$i + 5]) && $tokens[$i + 5][0] === T_WHITESPACE &&
						isset($tokens[$i + 6]) && is_array($tokens[$i + 6]) && $tokens[$i + 6][0] === T_STRING
					) {
						$className = $tokens[$i + 2][1];
						
						require_once $file;
						$ref = new ReflectionClass($className);
						if ($ref->isSubClassOf('TestCase')) {
							$tests[] = $ref->newInstance($className);
							//add to filter
							CoverageFilter::addFile($file);
						}
						unset($ref);
						
						$i += 6;
					}
				}
			}
			
			return $tests;
		}
		
	}

?>