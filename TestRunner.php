<?php

	class TestRunner {
		
		protected $tests;
		protected $name;
		protected $listeners;
		
		public function __construct($name, array $tests, array $listeners = array()) {
			$this->name      = $name;
			$this->tests     = $tests;
			$this->listeners = $listeners;
		}
		
		public function addTest(Testable $test) {
			$this->tests[] = $test;
			return $this;
		}
		
		public function addListener(TestListener $listener) {
			$this->listeners[] = $listener;
			return $this;
		}
		
		public function run() {
			foreach ($this->tests as $test) {
				if ($test instanceof Testable) {
					$test->run($this->listeners);
				} else {
					foreach ($this->listeners as $listener) {
						$listener->onFrameworkWarning('Unable to run test because it is not an instanceof Testable (' . gettype($test) . ')');
					}
				}
			}
		}
		
	}

?>