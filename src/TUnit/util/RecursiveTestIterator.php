<?php

	class RecursiveTestIterator extends ArrayIterator implements RecursiveIterator {
		
		public function getChildren() {
			return new self($this->current()->getTestResults());
		}
		
		public function hasChildren() {
			return $this->current() instanceof CombinedTestResult;
		}
		
	}

?>