<?php

	class TestCase implements Testable {
		
		protected $name;
		private   $autoVerify;
		
		private $testableMethods;
		
		const ANY           = -1;
		const AT_LEAST_ONCE = -2;
		
		
		public function __construct($name) {
			$this->name            = $name;
			$this->autoVerify      = true;
			$this->testableMethods = array();
		}
		
		public final function getName() {
			return $this->name;
		}
		
		public final function getAutoVerify() {
			return $this->autoVerify;
		}
		
		public final function setAutoVerify($autoVerify) {
			$this->autoVerify = (bool)$autoVerify;
		}
		
		protected function setUp() {
			
		}
		
		protected function tearDown() {
		
		}
		
		public function run(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->beforeTestCase($this);
			}
			
			$result = new CombinedTestResult();
			foreach ($this->getTestableMethods() as $testMethod) {
				$this->setUp();
				$result->addTestResult($testMethod->run($listeners));
				$this->tearDown();
			}
			
			foreach ($listeners as $listener) {
				$listener->afterTestCase($this);
			}
			
			return $result;
		}
		
		public final function getTestableMethods() {
			if (empty($this->testableMethods)) {
				$refClass = new ReflectionClass($this);
				$methods = array();
				foreach ($refClass->getMethods() as $method) {
					if (
						$method->getDeclaringClass()->getName() !== __CLASS__ &&
						preg_match('/^[\/\*\s]*@test\s*(?:\*\/)?$/m', $method->getDocComment())
					) {
						$methods[] = new TestMethod(Util::getClosure($this, $method->getName()), get_class($this) . '::' . $method->getName(), $this->getAutoVerify());
					}
				}
				
				$this->testableMethods = $methods;
			}
			
			return $this->testableMethods;
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
		
		public function count() {
			return count($this->getTestableMethods());
		}
		
		public function getTestCount() {
			return Util::countTests($this->testableMethods);
		}
		
	}

?>