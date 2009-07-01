<?php

	/**
	 * TestMethod
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Represents a single test method
	 *
	 * This class should only be used internally by the Testify framework.
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	/* internal */ class TestMethod implements Testable {
		
		/**
		 * @var bool
		 */
		protected $autoVerify;
		
		/**
		 * @var lambda
		 */
		protected $closure;
		
		/**
		 * Name of this test
		 *
		 * @var mixed
		 */
		protected $name;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  lambda $closure    A closure around a testable method
		 * @param  string $name       Name of the test
		 * @param  bool   $autoVerify
		 */
		public function __construct($closure, $name, $autoVerify) {
			$this->closure    = $closure;
			$this->autoVerify = (bool)$autoVerify;
			$this->name       = $name;
		}
		
		/**
		 * Gets the name of this test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return string
		 */
		public final function getName() {
			return $this->name;
		}
		
		/**
		 * Runs the test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestListener::beforeTestMethod()
		 * @uses    MockRegistry::getTrackers()
		 * @uses    InvocationTracker::verify()
		 * @uses    TestListener::onTestMethodPassed()
		 * @uses    handlTestFailure()
		 * @uses    TestListener::onTestMethodErred()
		 * @uses    createTestResult()
		 * @uses    TestListener::afterTestMethod()
		 * @uses    MockRegistry::reset()
		 * 
		 * @param  array $listeners
		 * @return TestResult
		 */
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
		
		/**
		 * Creates a test result
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  Exception $failure
		 * @throws InvalidArgumentException
		 * @return TestResult
		 */
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
		
		/**
		 * Handles a test failure
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestListener::onTestMethodFailed()
		 * @uses    TestListener::onTestMethodIgnored()
		 * @uses    TestListener::onTestMethodErred()
		 * 
		 * @param  TestFailure $failure
		 * @param  array       $listeners
		 */
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
		
		/**
		 * Gets the number of tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return int Always returns one
		 */
		public function count() {
			return 1;
		}
		
		/**
		 * Gets the number of suites, cases and methods
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    Util::countTests()
		 * 
		 * @return array
		 */
		public function getTestCount() {
			return Util::countTests(array($this));
		}
		
	}

?>