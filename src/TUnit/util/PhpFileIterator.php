<?php

	class RecursivePhpFileIterator extends RecursiveFilterIterator {
		
		public function __construct($dir) {
			parent::__construct(new RecursiveDirectoryIterator($dir));
		}
		
		public function accept() {
			return
				!$this->getInnerIterator()->isDot() && 
				strpos($this->current()->getPathName(), DIRECTORY_SEPARATOR . '.') === false && 
				substr($this->current()->getFileName(), -4) === '.php';
		}
		
	}
	
	class PhpFileIterator extends FilterIterator {
		
		public function __construct($dir) {
			parent::__construct(new DirectoryIterator($dir));
		}
		
		public function accept() {
			return
				!$this->getInnerIterator()->isDot() && 
				strpos($this->current()->getPathName(), DIRECTORY_SEPARATOR . '.') === false && 
				substr($this->current()->getFileName(), -4) === '.php';
		}
		
	}

?>