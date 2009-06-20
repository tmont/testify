<?php

	class TestListener {
		
		public function __construct() {
			
		}
		
		public function beforeTestRunner(TestRunner $runner) {
		
		}
		
		public function afterTestRunner(TestRunner $runner) {
			
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
		
		public function beforeTestMethod(TestMethod $method) {
		
		}
		
		public function afterTestMethod(TestMethod $method) {
		
		}
		
		public function onTestMethodFailed(TestMethod $method) {
		
		}
		
		public function onTestMethodPassed(TestMethod $method) {
		
		}
		
		public function onTestMethodErred(TestMethod $method) {
		
		}
		
		public function onTestMethodIgnored(TestMethod $method) {
		
		}
		
		public function onFrameworkError($message) {
		
		}
		
		public function onFrameworkWarning($message) {
		
		}
		
		public function publishTestResults($result) {
		
		}
		
		public function publishTestResult(TestResult $result) {
		
		}
		
	}

?>