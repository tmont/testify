<?php

	/* internal */ class TestMethod implements Testable {
		
		protected $autoVerify;
		protected $closure;
		protected $name;
		
		public function __construct($closure, $name, $autoVerify) {
			$this->closure    = $closure;
			$this->autoVerify = (bool)$autoVerify;
			$this->name       = $name;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function run(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->beforeTestMethod($this);
			}
			
			$result  = null;
			$failure = null;
			
			try {
				call_user_func($this->closure);
				
				//verify if necessary
				if ($this->autoVerify) {
					foreach (MockRegistry::getTrackers() as $tracker) {
						if (!$tracker->verify()) {
							throw new FailedTest('Verification of InvocationTracker failed');
						}
					}
				}
				
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
			
			foreach ($listeners as $listener) {
				$listener->afterTestMethod($this);
			}
			
			//reset mock object registry
			MockRegistry::reset();
			
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
		
		public function count() {
			return 1;
		}
		
		public function getTestCount() {
			return Util::countTests(array($this));
		}
		
	}

?>