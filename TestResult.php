<?php

	interface TestResult extends Countable {
		
		public function passed();
		
		public function failed();
		
	}

?>