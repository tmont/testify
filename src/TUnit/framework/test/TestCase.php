<?php

	class TestCase implements Testable {
		
		protected $name;
		
		const ANY           = -1;
		const AT_LEAST_ONCE = -2;
		
		protected static $mockStorage = array();
		
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
				if (preg_match('/^[\/\*\s]*@test\s*(?:\*\/)?$/m', $method->getDocComment())) {
					$methods[] = $method;
				}
			}
			
			return $methods;
		}
		
		protected function createMockObject($className, array $methods = array(), array $args = array(), $name = '', $callParent = true) {
			$creator = new MockObjectCreator($className, $callParent);
			
			foreach ($methods as $method) {
				$creator->addMethod($method);
			}
			
			return $creator->generate($args, $name);
		}
		
		protected function mock(MockObject $mock) {
			return new MockHandler($mock);
		}
		
		protected function ignore($message = '') {
			throw new IgnoredTest($message);
		}
		
		protected function fail($message = '') {
			throw new FailedTest($message);
		}
		
	}

?>