<?php

	class CoverageReporter {
		
		const UNUSED = -1;
		const DEAD   = -2;
		
		private function __construct() {}
		
		public static function createConsoleReport(array $coverageData) {
			$coverageData = CoverageFilter::filter($coverageData);
			echo "\nCode coverage information:\n\n";
			//print_r($coverageData);
			foreach ($coverageData as $file => $data) {
				fwrite(STDOUT, $file . "\n");
				$loc  = 0;
				$dloc = 0;
				$cloc = 0;
				
				foreach ($data as $line => $unitsCovered) {
					$loc++;
					if ($unitsCovered > 0) {
						$cloc++;
					} else if ($unitsCovered === self::DEAD) {
						$dloc++;
					}
				}
				
				fwrite(STDOUT, "  Covered:    $cloc\n");
				fwrite(STDOUT, "  Dead:       $dloc\n");
				fwrite(STDOUT, "  Executable: $loc (" . round($cloc / $loc * 100, 2) . "%)\n");
			}
		}
		
		public static function createXmlReport($file, array $coverageData) {
		
		}
		
		public static function createHtmlReport($dir, array $coverageData) {
			if (!is_dir($dir)) {
				throw new TUnitException($dir . ' is not a directory');
			}
			
			
		}
		
	}

?>