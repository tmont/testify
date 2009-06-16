<?php

	/* internal */ class TestMethod implements Testable {
		
		protected $testCase;
		protected $method;
		
		public function __construct(TestCase $testCase, ReflectionMethod $method) {
			$this->testCase = $testCase;
			$this->method   = $method;
		}
		
		public function getName() {
			return $this->method->getDeclaringClass()->getName() . '::' . $this->method->getName();
		}
		
		public function run(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->beforeTestMethod($this);
			}
			
			$result  = null;
			$failure = null;
			
			$this->testCase->setUp();
			try {
				$this->method->invoke($this->testCase);
				foreach ($listeners as $listener) {
					$listener->onTestMethodPassed($this);
				}
			} catch (TestFailure $failure) {
				$this->handleTestFailure($failure, $listeners);
			} catch (Exception $e) {
				//test for expected exception
				foreach ($listeners as $listener) {
					$listener->onTestMethodErred($this);
				}
				
				$failure = new ErredTest($e->getMessage(), $e);
			}
			
			$result = $this->createTestResult($failure);
			$this->testCase->tearDown();
			
			foreach ($listeners as $listener) {
				$listener->afterTestMethod($this);
			}
			
			return $result;
		}
		
		protected function createTestResult(Exception $failure = null) {
			if ($failure === null) {
				return new PassedTestResult($this);
			} else if ($failure instanceof ErredTest) {
				return new ErredTestResult($this, $failure);
			} else if ($failure instanceof FailedTest) {
				return new FailedTestResult($this, $failure);
			} else if ($failure instanceof IgnoredTest) {
				return new IgnoredTestResult($this, $failure);
			}
			
			throw new InvalidArgumentException('Unknown test failure type: ' . get_class($failure));
		}
		
		protected function handleTestFailure(TestFailure $failure, array $listeners) {
			if ($failure instanceof FailedTest) {
				foreach ($listeners as $listener) {
					$listener->onTestMethodFailed($this);
				}
			} else if ($failure instanceof IgnoredTest) {
				foreach ($listeners as $listener) {
					$listener->onTestMethodIgnored($this);
				}
			} else if ($failure instanceof ErredTest) {
				foreach ($listeners as $listener) {
					$listener->onTestMethodErred($this);
				}
			}
		}
		
	}

?>