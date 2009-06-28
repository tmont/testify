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
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @since   1.0
		 * @version 1.0
		 * @uses    getOption()
		 */
		protected function preRun() {
			parent::preRun();
			
			if ($this->getOption('coverage-html') !== null || $this->getOption('coverage-console') !== null/* || $this->getOption('coverage-xml')*/) {
				xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
			}
			
			$bootstrap = $this->getOption('bootstrap');
			if ($bootstrap !== null) {
				require_once $bootstrap;
			}
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @since   1.0
		 * @version 1.0
		 * @uses    getOption()
		 * @uses    CoverageReporter::createConsoleReport()
		 * @uses    CoverageReporter::createHtmlReport()
		 */
		protected function postRun() {
			$html    = $this->getOption('coverage-html');
			$console = $this->getOption('coverage-console');
			if ($html !== null || $console !== null) {
				$coverage = xdebug_get_code_coverage();
				xdebug_stop_code_coverage();
				if ($console !== null) {
					CoverageReporter::createConsoleReport($coverage);
				}
				if ($html !== null) {
					CoverageReporter::createHtmlReport($html, $coverage);
				}
				
				unset($coverage);
			}
		}
		
		/**
		 * Parses test runner options
		 *
		 * @author  Tommy Montgomery
		 * @since   1.0
		 * @version 1.0
		 * @uses    validateOptions()
		 * @uses    getAllowableOptions()
		 *
		 * @param  array $unparsed Unparsed options
		 * @return array The parsed options
		 */
		protected function parseOptions(array $unparsed) {
			$options = $this->getAllowableOptions();
			foreach ($options as $name => $default) {
				if (array_key_exists($name, $unparsed)) {
					$options[$name] = $unparsed[$name];
				}
			}
			
			$this->validateOptions($options);
			
			return $options;
		}
		
		/**
		 * Validates the options
		 *
		 * @author  Tommy Montgomery
		 * @since   1.0
		 * @version 1.0
		 *
		 * @param  array $options
		 * @throws {@link InvalidOptionException}
		 */
		protected function validateOptions(array $options) {
			if ($options['bootstrap'] !== null && !is_file($options['bootstrap'])) {
				throw new InvalidOptionException('bootstrap', 'Bootstrap must be a file');
			}
			if (!is_bool($options['recursive'])) {
				throw new InvalidOptionException('recursive', 'recursive must be a boolean');
			}
			if (/*$options['coverage-xml'] !== null || */$options['coverage-html'] !== null || $options['coverage-console'] !== null) {
				if (!extension_loaded('xdebug')) {
					throw new InvalidOptionException('coverage-(xml|html|console)', 'xdebug extension is not loaded');
				}
				/*if ($options['coverage-xml'] !== null) {
					$dir = dirname($options['coverage-xml']);
					if (!is_dir($dir)) {
						if (!mkdir($dir, 0777, true)) {
							throw new TUnitException('Could not create directory: ' . $dir);
						}
					}
				}*/
				if ($options['coverage-html'] !== null) {
					//create coverage directory if needed
					if (!is_dir($options['coverage-html'])) {
						if (!mkdir($options['coverage-html'], 0777, true)) {
							throw new TUnitException('Could not create directory: ' . $options['coverage-html']);
						}
					}
				}
			}
		}
		
		/**
		 * Gets allowable options and default values
		 *
		 * @author  Tommy Montgomery
		 * @since   1.0
		 * @version 1.0
		 *
		 * @return array
		 */
		public function getAllowableOptions() {
			return array(
				'bootstrap'        => null,
				'recursive'        => false,
				//'coverage-xml'     => null,
				'coverage-html'    => null,
				'coverage-console' => false
			);
		}
		
	}

?>