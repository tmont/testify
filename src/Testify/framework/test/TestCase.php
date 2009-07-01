<?php

	/**
	 * TestCase
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Represents a test case (collection of test methods)
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class TestCase implements Testable {
		
		/**
		 * Name of the test
		 *
		 * @var string
		 */
		protected $name;
		
		/**
		 * Whether to automatically verify this test
		 *
		 * @var bool
		 */
		private $autoVerify;
		
		/**
		 * Local cache of testable methods
		 *
		 * @var array
		 */
		private $testableMethods;
		
		/**
		 * Mock invocation count
		 *
		 * @var int
		 */
		const ANY           = -1;
		
		/**
		 * Mock invocation count
		 *
		 * @var int
		 */
		const AT_LEAST_ONCE = -2;
		
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $name
		 */
		public function __construct($name) {
			$this->name            = $name;
			$this->autoVerify      = true;
			$this->testableMethods = array();
		}
		
		/**
		 * Gets the name of the name of this test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return string
		 */
		public final function getName() {
			return $this->name;
		}
		
		/**
		 * Gets whether this test will automatically verify itself
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return bool
		 */
		public final function getAutoVerify() {
			return $this->autoVerify;
		}
		
		/**
		 * Sets whether this test will automatically verify itself
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  bool $autoVerify
		 */
		public final function setAutoVerify($autoVerify) {
			$this->autoVerify = (bool)$autoVerify;
		}
		
		/**
		 * Called before this test runs
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		protected function setUp() {
			
		}
		
		/**
		 * Called after this test runs
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 */
		protected function tearDown() {
		
		}
		
		/**
		 * Runs the test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    TestListener::beforeTestCase()
		 * @uses    getTestableMethods()
		 * @uses    setUp()
		 * @uses    CombinedTestResult::addTestResult()
		 * @uses    TestMethod::run()
		 * @uses    tearDown()
		 * @uses    TestListener::afterTestCase()
		 * 
		 * @param  array $listeners Array of {@link TestListener}s
		 * @return CombinedTestResult
		 */
		public function run(array $listeners) {
			foreach ($listeners as $listener) {
				$listener->beforeTestCase($this);
			}
			
			$result = new CombinedTestResult();
			foreach ($this->getTestableMethods() as $testMethod) {
				$this->setUp();
				$result->addTestResult($testMethod->run($listeners));
				$this->tearDown();
			}
			
			foreach ($listeners as $listener) {
				$listener->afterTestCase($this);
			}
			
			return $result;
		}
		
		/**
		 * Gets testable methods
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    Util::getClosure()
		 * @uses    getAutoVerify()
		 * 
		 * @return array Array of {@link TestMethod}s
		 */
		public final function getTestableMethods() {
			if (empty($this->testableMethods)) {
				$refClass = new ReflectionClass($this);
				$methods = array();
				foreach ($refClass->getMethods() as $method) {
					if (
						$method->getDeclaringClass()->getName() !== __CLASS__ &&
						preg_match('/^[\/\*\s]*@test\s*(?:\*\/)?$/m', $method->getDocComment())
					) {
						$methods[] = new TestMethod(Util::getClosure($this, $method->getName()), get_class($this) . '::' . $method->getName(), $this->getAutoVerify());
					}
				}
				
				$this->testableMethods = $methods;
			}
			
			return $this->testableMethods;
		}
		
		/**
		 * Creates a default mock object
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    MockObjectCreator::addMethod
		 * 
		 * @param  string $className  The name of the class to mock
		 * @param  array  $methods    The methods to mock
		 * @param  array  $args       The constructor arguments
		 * @param  string $name       The name of the mocked class, by default a random name is chosen
		 * @param  bool   $callParent Whether to call the parent constructor or not
		 * @return object A subclass of $className
		 */
		protected function createMockObject($className, array $methods = array(), array $args = array(), $name = '', $callParent = true) {
			$creator = new MockObjectCreator($className, $callParent);
			
			foreach ($methods as $method) {
				$creator->addMethod($method);
			}
			
			return $creator->generate($args, $name);
		}
		
		/**
		 * Wrapper for interacting with the mock object framework
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  MockObject $mock
		 * @return MockHandler
		 */
		protected function mock(MockObject $mock) {
			return new MockHandler($mock);
		}
		
		/**
		 * Ignores the test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $message
		 * @throws {@link IgnoredTest}
		 */
		protected function ignore($message = '') {
			throw new IgnoredTest($message);
		}
		
		/**
		 * Fails the test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $message
		 * @throws {@link FailedTest}
		 */
		protected function fail($message = '') {
			throw new FailedTest($message);
		}
		
		/**
		 * Gets the number of testable methods
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    getTestableMethods()
		 * 
		 * @return count
		 */
		public function count() {
			return count($this->getTestableMethods());
		}
		
		/**
		 * Gets the number of test suites, casese and methods in this test
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    Util::countTests()
		 * @uses    getTestableMethods()
		 * 
		 * @return array
		 */
		public function getTestCount() {
			return Util::countTests($this->getTestableMethods());
		}
		
	}

?>