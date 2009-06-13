<?php

	class FailedTestResult extends SingleTestResult {
		
		public function passed() {
			return false;
		}
		
		public function failed() {
			return true;
		}
		
	}

?>