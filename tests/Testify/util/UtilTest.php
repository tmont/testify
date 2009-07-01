<?php

	class UtilTest extends TestCase {
		
		/**
		 * @test
		 */
		public function exportObject() {
			Assert::equal('stdClass', Util::export(new stdClass()));
			Assert::equal(get_class($this), Util::export($this));
		}
		
		/**
		 * @test
		 */
		public function exportString() {
			Assert::equal('"foo"', Util::export('foo'));
			Assert::equal('"foofoofoof...ofoofoofoo"', Util::export('foofoofoofoofoofoofoofoofoo'));
		}
		
		/**
		 * @test
		 */
		public function exportDouble() {
			Assert::equal(10.7, Util::export(10.7));
		}
		
		/**
		 * @test
		 */
		public function exportNull() {
			Assert::equal('NULL', Util::export(null));
		}
		
		/**
		 * @test
		 */
		public function exportInteger() {
			Assert::equal(10, Util::export(10));
		}
		
		/**
		 * @test
		 */
		public function exportBoolean() {
			Assert::equal('true', Util::export(true));
		}
		
		/**
		 * @test
		 */
		public function exportResource() {
			Assert::equal('resource of type xml', Util::export(xml_parser_create()));
		}
		
		/**
		 * @test
		 */
		public function exportArray() {
			Assert::equal('array[3]', Util::export(array(1,2,3)));
		}
		
	}

?>