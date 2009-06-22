<?php

	/**
	 * Test runners
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Test runner for the console
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class ConsoleTestRunner extends TestRunner {
		
		/**
		 * Gets allowable options
		 *
		 * Currently supported options:
		 * - recursive (boolean)
		 * - bootstrap (string)
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return array
		 */
		protected function getAllowableOptions() {
			return array(
				'recursive' => 'boolean',
				'bootstrap' => 'string'
			);
		}
		
	}

?>