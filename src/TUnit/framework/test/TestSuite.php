<?php

	class TestSuite implements Testable {
		
		protected $name;
		protected $tests;
		
		public function __construct($name, array $tests = array()) {
			$this->name  = $name;
			$this->tests = $tests;
		}
		
		public function getName() {
			return $this->name;
		}
		
		protected function setUp() {
		
		}
		
		protected function tearDown() {
		
		}
		
		public function addTest(Testable $test) {
			$this->tests[] = $test;
			return $this;
		}
		
		public function run(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->beforeTestSuite($this);
			}
			
			$result = new CombinedTestResult();
			
			$this->setUp();
			foreach ($this->tests as $test) {
				if ($test instanceof Testable) {
					$result->addTestResult($test->run($listeners));
				} else {
					foreach ($this->listeners as $listener) {
						$listener->onFrameworkWarning('Unable to run test because it is not an instance of Testable (' . gettype($test) . ')');
					}
				}
			}
			$this->tearDown();
			
			foreach ($listeners as $listener) {
				$listener->afterTestSuite($this);
			}
			
			return $result;
		}
		
	}

?>