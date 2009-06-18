<?php

	class TestRunner {
		
		protected $tests;
		protected $listeners;
		
		public function __construct(array $tests = array(), array $listeners = array()) {
			$this->tests     = $tests;
			$this->listeners = $listeners;
		}
		
		public final function addTest(Testable $test) {
			$this->tests[] = $test;
			return $this;
		}
		
		public final function addListener(TestListener $listener) {
			$this->listeners[] = $listener;
			return $this;
		}
		
		public function runTests() {
			$results = array();
			foreach ($this->tests as $test) {
				if ($test instanceof Testable) {
					$results[] = $test->run($this->listeners);
				} else {
					foreach ($this->listeners as $listener) {
						$listener->onFrameworkWarning('Unable to run test because it is not an instance of Testable (' . gettype($test) . ')');
					}
				}
			}
			
			return $results;
		}
		
		public function publishResults(array $testResults) {
			foreach ($this->listeners as $listener) {
				$listener->publishTestResults($testResults);
			}
		}
		
		public function run() {
			self::printMeta();
			$this->publishResults($this->runTests());
		}
		
		public static function printMeta() {
			fwrite(STDOUT, Product::NAME . ' ' . Product::VERSION . ' (build date: ' . Product::DATE . ')' . "\n");
			fwrite(STDOUT, '  by ' . Product::AUTHOR . "\n\n");
		}
		
	}

?>