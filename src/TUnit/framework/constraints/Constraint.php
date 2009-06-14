<?php

	interface Constraint {
		
		public function fail($message = '');
		
		public function toString($message);
		
		public function evaluate();
		
	}

?>