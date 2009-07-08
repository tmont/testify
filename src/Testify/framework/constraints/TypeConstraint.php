<?php

	class TypeConstraint extends SimpleConstraint {
		
		protected $callback;
		
		public function __construct($type, $value) {
			parent::__construct($value);
			
			if (!function_exists('is_' . $type)) {
				throw new InvalidArgumentException('Invalid type constraint: ' . $type);
			}
			
			$this->callback = 'is_' . strtolower($type);
		}
		
		public function evaluate() {
			return call_user_func($this->callback, $this->value);
		}
		
		protected function getFailureMessage() {
			$type = substr($this->callback, strpos($this->type, 'is_') + 3);
			return Util::export($this->value) . " is of type \"$type\"";
		}
		
	}

?>