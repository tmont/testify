<?php

	class ErredTestResult extends SingleTestResult {
		
		public function passed() {
			return false;
		}
		
		public function failed() {
			return true;
		}
		
	}

?>