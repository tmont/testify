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
		 * @see setFiles()
		 * @var array
		 */
		protected $files;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  array $tests
		 * @param  array $listeners
		 * @param  array $options
		 */
		public function __construct(array $tests = array(), array $listeners = array(), array $options = array()) {
			parent::__construct($tests, $listeners, $options);
			$this->files = array();
		}
		
		/**
		 * Sets the files to parse for tests
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  array $files Array of files and/or directories to parse for tests
		 * @return ConsoleTestRunner
		 */
		public final function setFiles(array $files) {
			$this->files = $files;
			return $this;
		}
		
		/**
		 * {@inheritdoc}
		 *
		 * @author  Tommy Montgomery
		 * @since   1.0
		 * @version 1.0
		 * @uses    getOption()
		 */
		protected function preRun() {
			if ($this->getOption('coverage-html') || $this->getOption('coverage-console')/* || $this->getOption('coverage-xml')*/) {
				xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
			}
			
			$bootstrap = $this->getOption('bootstrap');
			if ($bootstrap !== null) {
				require_once $bootstrap;
			}
			
			
			//accumulate tests from files
			if (!empty($this->files)) {
				foreach (TestAccumulator::getTests($this->files, $this->getOption('recursive')) as $test) {
					$this->addTest($test);
				}
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
			if ($html !== null || $console === true) {
				$coverage = xdebug_get_code_coverage();
				xdebug_stop_code_coverage();
				if ($console === true) {
					CoverageReporter::createConsoleReport($coverage);
				}
				if ($html !== null) {
					CoverageReporter::createHtmlReport($html, $coverage, $this->getOption('coverage-renderer'));
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
					
					//check the renderer
					if ($options['coverage-renderer'] !== null) {
						if (!in_array($options['coverage-renderer'], array('png'))) {
							throw new InvalidOptionException('coverage-renderer', 'unknown renderer: ' . $options['renderer']);
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
				'bootstrap'         => null,
				'recursive'         => false,
				'coverage-html'     => null,
				'coverage-renderer' => null,
				'coverage-console'  => false
			);
		}
		
	}

?>