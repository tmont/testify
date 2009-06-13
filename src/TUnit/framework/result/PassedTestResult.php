<?php

	class PassedTestResult extends SingleTestResult {
		
		public function passed() {
			return true;
		}
		
		public function failed() {
			return false;
		}
		
	}

?>