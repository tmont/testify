<?php

	/**
	 * PHP file iterators
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Class for recursively iterating over PHP files
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class RecursivePhpFileIterator extends RecursiveFilterIterator {
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  mixed $dir
		 */
		public function __construct($dir) {
			parent::__construct(new RecursiveDirectoryIterator($dir));
		}
		
		/**
		 * Defines acceptance criteria for iteration
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function accept() {
			return
				!$this->getInnerIterator()->isDot() && 
				strpos($this->current()->getPathName(), DIRECTORY_SEPARATOR . '.') === false && 
				substr($this->current()->getFileName(), -4) === '.php';
		}
		
	}
	
	/**
	 * Iterates over PHP files
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class PhpFileIterator extends FilterIterator {
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $dir
		 */
		public function __construct($dir) {
			parent::__construct(new DirectoryIterator($dir));
		}
		
		/**
		 * Defines acceptance criteria for iteration
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public function accept() {
			return
				!$this->getInnerIterator()->isDot() && 
				strpos($this->current()->getPathName(), DIRECTORY_SEPARATOR . '.') === false && 
				substr($this->current()->getFileName(), -4) === '.php';
		}
		
	}

?>