<?php

	abstract class TestRunner implements RecursivelyCountable {
		
		protected $tests;
		protected $listeners;
		protected $options;
		
		private $startTime;
		private $endTime;
		
		public function __construct(array $tests = array(), array $listeners = array(), array $options = array()) {
			$this->tests     = $tests;
			$this->listeners = $listeners;
			$this->options   = (!empty($options)) ? $this->parseOptions($options) : array();
			
			$this->startTime = -1;
			$this->endTime   = -1;
		}
		
		public final function getStartTime() {
			return $this->startTime;
		}
		
		public final function getEndTime() {
			return $this->endTime;
		}
		
		public final function getTests() {
			return $this->tests;
		}
		
		public final function warn($message) {
			foreach ($this->listeners as $listener) {
				$listener->onFrameworkWarning($message);
			}
		}
		
		public final function error($message) {
			foreach ($this->listeners as $listener) {
				$listener->onFrameworkError($message);
			}
		}
		
		public final function addTest(Testable $test) {
			$this->tests[] = $test;
			return $this;
		}
		
		public final function addListener(TestListener $listener) {
			$this->listeners[] = $listener;
			return $this;
		}
		
		public final function setOptions(array $options) {
			$this->options = $this->parseOptions($options);
			return $this;
		}
		
		protected final function parseOptions(array $unparsed) {
			$allowedOptions = $this->getAllowableOptions();
			$options = array_fill_keys(array_key($allowedOptions), null);
			foreach ($unparsed as $option => $value) {
				if (!isset($allowedOptions[$option])) {
					throw new InvalidOptionException($option);
				}
				if (gettype($value) !== $allowedOptions[$option]) {
					throw new InvalidOptionException($option, 'Expected a value of type ' . $allowedOptions[$option] . ', got ' . gettype($value));
				}
				
				$options[$option] = $value;
			}
			
			return $options;
		}
		
		public function runTests() {
			$results = array();
			foreach ($this->tests as $test) {
				if ($test instanceof Testable) {
					try {
						$results[] = $test->run($this->listeners);
					} catch (TUnitException $e) {
						$this->error($e->getMessage());
					}
				} else {
					$this->warn('Unable to run test because it is not an instance of Testable (' . gettype($test) . ')');
				}
			}
			
			return $results;
		}
		
		public function publishResults(array $testResults) {
			foreach ($this->listeners as $listener) {
				$listener->publishTestResults($testResults);
			}
		}
		
		public final function run() {
			foreach ($this->listeners as $listener) {
				$listener->beforeTestRunner($this);
			}
			
			$this->startTime = microtime(true);
			$this->publishResults($this->runTests());
			$this->endTime = microtime(true);
			
			foreach ($this->listeners as $listener) {
				$listener->afterTestRunner($this);
			}
		}
		
		public function count() {
			return count($this->tests);
		}
		
		public function getTestCount() {
			return Util::countTests($this->tests);
		}
		
		protected abstract function getAllowableOptions();
		
	}

?>