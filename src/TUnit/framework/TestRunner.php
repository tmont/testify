<?php

	class ConsoleTestRunner extends BaseTestRunner {
		
		public function run() {
			self::printMeta();
			$this->publishResults($this->runTests());
		}
		
		protected function getAllowableOptions() {
			return array(
				'recursive' => 'boolean',
				'bootstrap' => 'string'
			);
		}
		
		public static function printMeta() {
			fwrite(STDOUT, Product::NAME . ' ' . Product::VERSION . ' (build date: ' . Product::DATE . ')' . "\n");
			fwrite(STDOUT, '  by ' . Product::AUTHOR . "\n\n");
		}
		
	}

?>