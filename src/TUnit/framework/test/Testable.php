<?php

	interface RecursivelyCountable extends Countable {
		public function getTestCount();
	}

	interface Testable extends RecursivelyCountable {
		
		public function run(array $listeners);
		
		public function getName();
		
	}

?>