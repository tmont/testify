<?php

	/**
	 * Util
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Utilities
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class Util {
		
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
		 * Gets a human-readable version of an object
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $var
		 * @return string
		 */
		public static function export($var) {
			switch (strtolower(gettype($var))) {
				case 'object':
					return get_class($var);
				case 'string':
					if (strlen($var) > 20) {
						return '"' . substr($var, 0, 10) . '...' . substr($var, -10) . '"';
					}
					
					return '"' . $var . '"';
				case 'double':
				case 'null':
				case 'boolean':
				case 'integer':
					return var_export($var, true);
				case 'resource':
					return 'resource of type ' . get_resource_type($var);
				case 'array':
					return 'array[' . count($var) . ']';
			}
		}
		
		/**
		 * Builds a parameter definition for a method
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  ReflectionMethod $method
		 * @return string
		 */
		public static function buildParameterDefinition(ReflectionMethod $method) {
			$paramList = '';
			foreach ($method->getParameters() as $i => $param) {
				if ($param->getClass()) {
					$paramList .= $param->getClass()->getName() . ' ';
				} else if ($param->isArray()) {
					$paramList .= 'array ';
				}
				
				if ($param->isPassedByReference()) {
					$paramList .= '&';
				}
				
				$paramList .= self::repairParameterName($param->getName(), $i);
				
				if ($param->isOptional()) {
					$paramList .= ' = ';
					if ($param->isDefaultValueAvailable()) {
						if (is_array($param->getDefaultValue())) {
							//removes annoying spaces in parens
							$paramList .= 'array()';
						} else {
							$paramList .= var_export($param->getDefaultValue(), true);
						}
					} else {
						$paramList .= 'null';
					}
				}
				
				$paramList .= ', ';
			}
			
			return rtrim($paramList, ', ');
		}
		
		/**
		 * Repairs a parameter name from ReflectionParameter->getName()
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $name
		 * @param  int    $position
		 * @return string
		 */
		private static function repairParameterName($name, $position) {
			if (empty($name)) {
				$name = 'param' . $position;
			}
			
			return $name;
		}
		
		/**
		 * Builds a parameter list suitable for eval()
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    repairParameterName()
		 * 
		 * @param  ReflectionMethod $method
		 * @return string
		 */
		public static function buildParameterNameList(ReflectionMethod $method) {
			$list = '';
			foreach ($method->getParameters() as $param) {
				$list .= '$' . self::repairParamterName($param->getName()) . ', ';
			}
			
			return rtrim($list, ', ');
		}
		
		/**
		 * Flattens a multi-dimensional array into one dimension
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $arr
		 * @return array
		 */
		public static function arrayFlatten($arr) {
			$flattened = array();
			if (is_array($arr)) {
				foreach ($arr as $value) {
					$flattened = array_merge($flattened, self::arrayFlatten($value));
				}
			} else {
				$flattened[] = $arr;
			}
			
			return $flattened;
		}
		
		/**
		 * DGets the number of all test suites, cases and methods
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    mergeTestCount()
		 * 
		 * @param  array $tests
		 * @return array An array with keys "suite", "case" and "method"
		 */
		public static function countTests(array $tests) {
			$counts = array(
				'suite'  => 0,
				'case'   => 0,
				'method' => 0
			);
			
			foreach ($tests as $test) {
				if ($test instanceof TestSuite) {
					$counts['suite']++;
					$counts = self::mergeTestCount($counts, self::countTests($test->getTests()));
				} else if ($test instanceof TestCase) {
					$counts['case']++;
					$counts = self::mergeTestCount($counts, self::countTests($test->getTestableMethods()));
				} else if ($test instanceof TestMethod) {
					$counts['method']++;
				}
			}
			
			return $counts;
		}
		
		/**
		 * Used by countTests()
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  array $arr1
		 * @param  array $arr2
		 * @return array
		 */
		private static function mergeTestCount(array $arr1, array $arr2) {
			$arr1['suite']  += $arr2['suite'];
			$arr1['case']   += $arr2['case'];
			$arr1['method'] += $arr2['method'];
			return $arr1;
		}
		
		/**
		 * Gets a method closure
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @link    http://php.net/manual/en/language.oop5.reflection.php#90964
		 * 
		 * @param  object $object
		 * @param  string $method
		 * @throws InvalidArgumentException
		 * @return lambda
		 */
		public static function getClosure($object, $method) {
			if (!is_object($object)) {
				throw new InvalidArgumentException('1st argument must be an object');
			}
			
			$closure = create_function('',
				'$args = func_get_args();
				static $obj = null;
				
				if ($obj === null && isset($args[0]) && is_object($args[0])) {
					$obj = $args[0];
					return;
				}
				
				return call_user_func_array(array($obj, \'' . $method . '\'), $args);'
			);
			
			$closure($object);
			return $closure;
		}
		
		public static function getClassNamesFromFile($file) {
			$tokens  = token_get_all(file_get_contents($file));
			$classes = array();
			for ($i = 0, $len = count($tokens); $i < $len; $i++) {
				if (
					is_array($tokens[$i]) && $tokens[$i][0] === T_CLASS &&
					isset($tokens[$i + 1]) && is_array($tokens[$i + 1]) && $tokens[$i + 1][0] === T_WHITESPACE &&
					isset($tokens[$i + 2]) && is_array($tokens[$i + 2]) && $tokens[$i + 2][0] === T_STRING
				) {
					$classes[] = $tokens[$i + 2][1];
					$i += 2;
				}
			}
			
			return $classes;
		}
		
	}

?>