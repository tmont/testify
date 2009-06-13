<?php

	class IgnoredTestResult extends SingleTestResult {
		
		public function passed() {
			return false;
		}
		
		public function failed() {
			return false;
		}
		
	}

?>