<?php

	class TestListener {
		
		protected $reporter;
		
		public function __construct(TestReporter $reporter) {
			$this->reporter = $reporter;
		}
		
		public function beforeTestSuite(TestSuite $suite) {
		
		}
		
		public function afterTestSuite(TestSuite $suite) {
		
		}
		
		public function beforeTestCase(TestCase $test) {
		
		}
		
		public function afterTestCase(TestCase $test) {
		
		}
		
		public function onTestCaseFailed(TestCase $test) {
		
		}
		
		public function onTestCasePassed(TestCase $test) {
		
		}
		
		public function onTestCaseErred(TestCase $test) {
		
		}
		
		public function beforeTestMethod(ReflectionMethod $method) {
		
		}
		
		public function afterTestMethod(ReflectionMethod $method) {
		
		}
		
		public function onTestMethodFailed(ReflectionMethod $method) {
		
		}
		
		public function onTestMethodPassed(ReflectionMethod $method) {
		
		}
		
		public function onTestMethodErred(ReflectionMethod $method) {
		
		}
		
		public function onTestMethodIgnored(ReflectionMethod $method) {
		
		}
		
		public function onFrameworkError($message) {
		
		}
		
		public function onFrameworkWarning($message) {
		
		}
		
	}

?>