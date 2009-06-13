<?php

	class Util {
		
		private function __construct() {}
		
		public static function export($var) {
			switch (strtolower(gettype($var))) {
				case 'object':
					return get_class($var);
				case 'string':
					if (strlen($var) > 47) {
						return substr($var, 0, 20) . '...' . substr($var, floor(strlen($var) / 2) - 3, 7) . '...' . substr($var, -20);
					}
					
					return $var;
				case 'double':
				case 'null':
				case 'boolean':
				case 'integer':
					return var_export($var, true);
				case 'resource':
					return 'resource of type ' . get_resource_type($var);
			}
		}
		
	}

?>