<?php

	class TestCase implements Testable {
		
		protected $name;
		
		public function __construct($name) {
			$this->name = $name;
		}
		
		protected function setUp() {
		
		}
		
		protected function tearDown() {
		
		}
		
		public function run(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->onBeforeTestCase($this);
			}
			
			foreach ($this->getTestableMethods() as $method) {
				$this->runTestMethod($method);
			}
			
			foreach ($listeners as $listener) {
				$listener->onAfterTestCase($this);
			}
		}
		
		public function runTestMethod(ReflectionMethod $method, array $listeners) {
			foreach ($listeners as $listener) {
				$listener->beforeTestMethod($method);
			}
			
			$testPassed = false;
			$result     = null;
			$failure    = null;
			
			$this->setUp();
			try {
				$method->invoke($this);
				$testPassed = true;
			} catch (TestFailure $failure) {
				if ($failure instanceof FailedTest) {
					foreach ($listeners as $listener) {
						$listener->onTestMethodFailed($method);
					}
				} else if ($failure instanceof IgnoredTest) {
					foreach ($listeners as $listener) {
						$listener->onTestMethodIgnored($method);
					}
				} else if ($failure instanceof ErredTest) {
					foreach ($listeners as $listener) {
						$listener->onTestMethodErred($method);
					}
				}
			} catch (Exception $e) {
				//test for expected exception
				foreach ($listeners as $listener) {
					$listener->onTestMethodErred($method);
				}
			}
			
			$result = $this->createTestResult($method, $failure);
			$this->tearDown();
			
			foreach ($listeners as $listener) {
				$listener->afterTestMethod($method);
			}
		}
		
		protected function createTestResult(ReflectionMethod $method, TestFailure $failure = null) {
			if ($failure === null) {
				return new PassedTestResult($method);
			} else if ($failure instanceof FailedTest) {
				return new FailedTestResult($method, $failure);
			} else if ($failure instanceof ErredTest) {
				return new ErredTestResult($method, $failure);
			} else if ($failure instanceof IgnoredTest) {
				return new IgnoredTestResult($method, $failure);
			}
			
			throw new InvalidArgumentException('Unknown test failure type: ' . get_class($failure));
		}
		
		protected final function getTestableMethods() {
			$refClass = new ReflectionClass($this);
			$methods = array();
			foreach ($refClass->getMethods() as $method) {
				if (preg_match('/^[\*\s]*@test\s*$/m', $method->getDocBlock())) {
					$methods[] = $method;
				}
			}
			
			return $methods;
		}
		
	}

?>