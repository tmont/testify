<?php

	class TestCase implements Testable {
		
		protected $name;
		
		public function __construct($name) {
			$this->name = $name;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function setUp() {
		
		}
		
		public function tearDown() {
		
		}
		
		public function run(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->beforeTestCase($this);
			}
			
			$result = new CombinedTestResult();
			foreach ($this->getTestableMethods() as $method) {
				$testMethod = new TestMethod($this, $method);
				$result->addTestResult($testMethod->run($listeners));
			}
			
			foreach ($listeners as $listener) {
				$listener->afterTestCase($this);
			}
			
			return $result;
		}
		
		protected final function getTestableMethods() {
			$refClass = new ReflectionClass($this);
			$methods = array();
			foreach ($refClass->getMethods() as $method) {
				if (preg_match('/^[\*\s]*@test\s*$/m', $method->getDocComment())) {
					$methods[] = $method;
				}
			}
			
			return $methods;
		}
		
	}

?>