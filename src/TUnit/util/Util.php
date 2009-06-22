<?php

	class Util {
		
		private function __construct() {}
		
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
		
		public static function buildParameterDefinition(ReflectionMethod $method) {
			$paramList = '';
			foreach ($method->getParameters() as $i => $param) {
				if ($param->getClass()) {
					$paramList .= $param->getClass() . ' ';
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
						$paramList .= var_export($param->getDefaultValue(), true);
					} else {
						$paramList .= 'null';
					}
				}
				
				$paramList .= ',';
			}
			
			return rtrim($paramList, ', ');
		}
		
		private static function repairParameterName($name, $position) {
			if (empty($name)) {
				$name = 'param' . $position;
			}
			
			return $name;
		}
		
		public static function buildParameterNameList(ReflectionMethod $method) {
			$list = '';
			foreach ($method->getParameters() as $param) {
				$list .= '$' . self::repairParamterName($param->getName()) . ', ';
			}
			
			return rtrim($list, ', ');
		}
		
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
		
		private static function mergeTestCount(array $arr1, array $arr2) {
			$arr1['suite']  += $arr2['suite'];
			$arr1['case']   += $arr2['case'];
			$arr1['method'] += $arr2['method'];
			return $arr1;
		}
		
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
		
	}

?>