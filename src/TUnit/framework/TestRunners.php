<?php

	class ConsoleTestRunner extends TestRunner {
		
		protected function getAllowableOptions() {
			return array(
				'recursive' => 'boolean',
				'bootstrap' => 'string'
			);
		}
		
	}

?>