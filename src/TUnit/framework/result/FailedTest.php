<?php

	/**
	 * TestFailure, FailedTest, ErredTest, IgnoredTest
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Represents a test failure
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class TestFailure extends Exception {
		
		/**
		 * @var Exception|null
		 */
		protected $innerException;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string    $message
		 * @param  Exception $innerException
		 */
		public function __construct($message = '', Exception $innerException = null) {
			parent::__construct($message);
			
			$this->innerException = $innerException;
		}
		
		/**
		 * Gets the stack trace for the test failure
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return string
		 */
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
	
	/**
	 * A failed test
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class FailedTest extends TestFailure {}
	
	/**
	 * An erred test
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class ErredTest extends FailedTest {}
	
	/**
	 * An ignored test
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class IgnoredTest extends TestFailure {}

?>