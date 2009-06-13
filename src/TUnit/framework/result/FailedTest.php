<?php

	class TestFailure extends Exception {
		
		public function getStackTrace() {
			$trace = $this->getTrace();
			$count = 1;
			$stackTrace = array();
			foreach (array_slice($trace, 2) as $i => $frame) {
				$line = '[' . ($i + 1) . '] ';
				if (isset($frame['file'], $frame['line'])) {
					if ($frame['file'] === dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'TestMethod.php') {
						break;
					}
					$line .= $frame['file'] . ' (' . $frame['line'] . ') ';
				} else {
					$line .= '<internal function> ';
				}
				
				if (isset($frame['class']) || isset($frame['function'])) {
					if (isset($frame['class'], $frame['type'], $frame['function']) && !empty($frame['type'])) {
						$line .= $frame['class'] . $frame['type'] . $frame['function'] . '(';
					} else {
						$line .= $frame['function'] . '(';
					}
					
					if (isset($frame['args']) && !empty($frame['args'])) {
						$line .= implode(', ', array_map('TestFailure::transformArgs', $frame['args']));
					}
					
					$line .= ')';
				}
				
				$stackTrace[] = $line;
			}
			
			return implode("\n", $stackTrace);
		}
		
		protected static function transformArgs($value) {
			return Util::export($value);
		}
		
	}
	
	class FailedTest extends TestFailure {}
	class ErredTest extends FailedTest {}
	class IgnoredTest extends TestFailure {}

?>