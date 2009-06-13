<?php

	class TestFailure extends Exception {}
	class ErredTest extends FailedTest {}
	class IgnoredTest extends FailedTest {}
	class FailedTest extends TestFailure {}

?>