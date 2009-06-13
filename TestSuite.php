<?php

	class TestSuite implements Testable {
		
		protected $name;
		protected $tests;
		
		public function __construct($name, array $tests) {
			$this->name = $name;
			$this->tests = $tests;
		}
		
		protected function setUp() {
		
		}
		
		protected function tearDown() {
		
		}
		
		public function run(array $listeners) {
			$this->setUp();
			foreach ($this->tests as $test) {
				if ($test instanceof self) {
					foreach ($this->listeners as $listener) {
						$listener->beforeTestSuite($test);
					}
					
					$result = $test->run($listeners);
					
					foreach ($this->listeners as $listener) {
						$listener->afterTestSuite($test);
					}
				} else if ($test instanceof TestCase) {
					$test->run($listeners);
				} else {
					foreach ($this->listeners as $listener) {
						$listener->onFrameworkWarning('Unable to run test because it is not an instanceof Testable (' . gettype($test) . ')');
					}
				}
			}
			$this->tearDown();
		}
		
	}

?>