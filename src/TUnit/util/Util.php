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
		
		private static repairParameterName($name, $position) {
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
		
	}

?>