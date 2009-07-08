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
		
		/**
		 * @test
		 */
		public function buildParameterDefinition() {
			$refMethod = new ReflectionMethod($this, __FUNCTION__);
			Assert::isEmpty(Util::buildParameterDefinition($refMethod));
			
			$refMethod = new ReflectionMethod('ReflectionMethod', 'invokeArgs');
			Assert::equal('$object, array $args', Util::buildParameterDefinition($refMethod));
			
			$refMethod = new ReflectionMethod('ReflectionClass', 'getStaticPropertyValue');
			Assert::equal('$name, $default = null', Util::buildParameterDefinition($refMethod));
		}
		
		/**
		 * @test
		 */
		public function arrayFlatten() {
			$arr = array(
				'foo' => 'bar',
				7 => array(
					0 => array(
						1 => array(
							2 => array(
								3
							)
						)
					),
					'baz',
					'bat' => array(
						'foobie'
					)
				),
				8, 
				'yay' => array()
			);
			
			$expected = array('bar', 3, 'baz', 'foobie', 8);
			
			Assert::identical($expected, Util::arrayFlatten($arr), 'arrays are not identical');
		}
		
		/**
		 * @test
		 */
		public function arithmetic() {
			Assert::greaterThan(0, 1);
			Assert::greaterThanOrEqualTo(0, 0);
			
			Assert::lessThan(1, 0);
			Assert::lessThanOrEqualTo(0, 0);
		}
		
		/**
		 * @test
		 */
		public function types() {
			Assert::isInt(0);
			Assert::isNotInt('0');
			
			Assert::isFloat(1.5);
			Assert::isNotFloat(true);
			
			Assert::isString('foo');
			Assert::isNotString(1);
			
			Assert::isBool(false);
			Assert::isNotBool(1);
			
			Assert::isArray(array());
			Assert::isNotArray(new stdClass());
			
			Assert::numeric('1.5');
			Assert::numeric(1.0);
			Assert::numeric(1);
			Assert::numeric(1e10);
			Assert::numeric(0x12);
			Assert::notNumeric(array());
		}
		
		/**
		 * @test
		 */
		public function arrays() {
			$arr = array(
				'foo' => 'bar',
				7 => new stdClass()
			);
			
			Assert::arrayHasKey('foo', $arr);
			Assert::arrayHasKey(7, $arr);
			Assert::arrayNotHasKey('bar', $arr);
			Assert::arrayNotHasKey(0, $arr);
			
			Assert::arrayHasValue('bar', $arr);
			Assert::arrayHasValue(new stdClass(), $arr);
			Assert::arrayNotHasValue(null, $arr);
		}
		
	}

?>