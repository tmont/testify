<?php

	class TestFailure extends Exception {
		
		protected $innerException;
		
		public function __construct($message = '', Exception $innerException = null) {
			parent::__construct($message);
			
			$this->innerException = $innerException;
		}
		
		public function getStackTrace() {
			$trace = ($this->innerException !== null) ? $this->innerException->getTrace() : $this->getTrace();
			$count = 1;
			$stackTrace = array();
			//array_slice($trace, 2)
			foreach ($trace as $i => $frame) {
				$line = '[' . ($i + 1) . '] ';
				if (isset($frame['file'], $frame['line'])) {
					if ($frame['file'] === dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'TestMethod.php') {
						break;
					}
					
					$line .= $frame['file'] . ' (line ' . $frame['line'] . ') ';
					$line .= "\n      ";
				}
				
				if (isset($frame['class']) || isset($frame['function'])) {
					if (isset($frame['class'], $frame['type'], $frame['function']) && !empty($frame['type'])) {
						$line .= $frame['class'] . $frame['type'] . $frame['function'] . '(';
					} else {
						$line .= $frame['function'] . '(';
					}
					
					if (isset($frame['args']) && !empty($frame['args'])) {
						$line .= implode(', ', array_map('Util::export', $frame['args']));
					}
					
					$line .= ')';
				}
				
				$stackTrace[] = $line;
			}
			
			return implode("\n", $stackTrace);
		}
		
	}
	
	class FailedTest extends TestFailure {}
	class ErredTest extends FailedTest {}
	class IgnoredTest extends TestFailure {}

?>