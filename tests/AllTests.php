<?php

	class AllTests extends TestSuite {
		
		public function __construct() {
			CoverageFilter::clear();
			CoverageFilter::addDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . Product::NAME . DIRECTORY_SEPARATOR . 'external');
			
			parent::__construct('All Testify Tests', TestAccumulator::getTestsFromDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Testify'));
		}
		
	}

?>