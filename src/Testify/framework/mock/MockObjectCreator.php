<?php

	/**
	 * File containing MockObject, MockObjectCreator
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * MockObject dummy interface
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	interface MockObject {}

	/**
	 * Class MockObjectCreator
	 *
	 * @package Testify
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class MockObjectCreator {
		
		/**
		 * The object to mock
		 *
		 * @var ReflectionClass
		 */
		protected $referenceObject;
		
		/**
		 * The methods to mock
		 *
		 * @var mixed
		 */
		protected $methods;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $class      The name of the class or interface to mock
		 * @param  bool   $callParent Whether to call the parent constructor
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 */
		public function __construct($class, $callParent = true) {
			if (!class_exists($class) && !interface_exists($class)) {
				throw new InvalidArgumentException('The class "' . $class . '" does not exist');
			}
			
			$refClass = new ReflectionClass($class);
			
			if ($refClass->isFinal()) {
				throw new LogicException('The class "' . $class . '" is final and cannot be mocked');
			}
			
			$constructor = $refClass->getConstructor();
			if ($constructor === null) {
				$constructor = '__construct';
				$callParent = false;
			} else {
				$constructor = $constructor->getName();
			}
			
			$this->referenceObject = $refClass;
			
			$this->methods         = array(
				'default' => array(
					$constructor => array(
						'body'        => '',
						'call_parent' => (bool)$callParent
					)
				),
				'generic' => array()
			);
		}
		
		/**
		 * Adds a method to mock
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    methodIsMockable()
		 * 
		 * @param  string $methodName Name of the method to mock
		 * @param  bool   $callParent Whether to call the parent
		 * @param  string $body       Custom PHP code to execute in the method body
		 * @throws LogicException if the method is not mockable
		 * @return MockObjectCreator
		 */
		public function addMethod($methodName, $callParent = false, $body = '') {
			$methodType = 'generic';
			if ($this->referenceObject->hasMethod($methodName)) {
				$method = $this->referenceObject->getMethod($methodName);
				if (!$this->methodIsMockable($method)) {
					throw new LogicException('The method "' . $methodName . '" is static, private or final and cannot be mocked');
				}
				
				if ($method->isConstructor() || $method->isDestructor()) {
					$body = '';
				}
				
				$methodType = 'default';
			} else {
				$callParent = false;
			}
			
			$this->methods[$methodType][$methodName] = array(
				'body'        => strval($body),
				'call_parent' => (bool)$callParent
			);
			
			return $this;
		}
		
		/**
		 * Determines if a method is mockable
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  ReflectionMethod $method
		 * @return bool
		 */
		protected final function methodIsMockable(ReflectionMethod $method) {
			return !$method->isFinal() && !$method->isPrivate() && !$method->isStatic();
		}
		
		/**
		 * Generates the mock object
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    generateClassDefinition()
		 * @uses    MockRegistry::addClass()
		 * 
		 * @param  array  $constructorArgs Arguments to pass to the constructor
		 * @param  string $name            Custom name to give the newly created mock object, by default
		 *                                 a random, unused name is chosen
		 * @throws LogicException if a name is given that is already in use
		 * @return object
		 */
		public function generate(array $constructorArgs = array(), $name = '') {
			if (empty($name)) {
				$className = $this->referenceObject->getName();
				do {
					$name = 'Mock_' . $className . '_' . uniqid();
				} while (class_exists($name) || interface_exists($name));
			}
			
			if (class_exists($name) || interface_exists($name)) {
				throw new LogicException('Cannot use the name "' . $name . '" for mock object because the class or interface already exists');
			}
			
			$code = $this->generateClassDefinition($name);
			eval($code);
			
			MockRegistry::addClass($name);
			
			$obj = new ReflectionClass($name);
			$obj = $obj->newInstanceArgs($constructorArgs);
			return $obj;
		}
		
		/**
		 * Generates the class definition of the mock object
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * @uses    generateMethodDefinition()
		 * 
		 * @param  string $name The name of the class
		 * @return string Executable PHP code that will generate a class when eval()'d
		 */
		protected function generateClassDefinition($name) {
			$code = 'class ' . $name . ' ';
			if ($this->referenceObject->isInterface()) {
				$code .= 'implements ' . $this->referenceObject->getName() . ', MockObject';
			} else {
				$code .= 'extends ' . $this->referenceObject->getName() . ' implements MockObject';
			}
			
			$code .= " {\n";
			
			foreach ($this->methods as $type => $methods) {
				foreach ($methods as $method => $methodData) {
					$code .= $this->generateMethodDefinition($type, $method, $methodData) . "\n";
				}
			}
			
			$code .= '}';
			return $code;
		}
		
		/**
		 * Generates a method definition
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $type       "default" or "generic"
		 * @param  string $name       Name of the method
		 * @param  array  $methodData Should have keys "call_parent" and "body"
		 * @return string
		 */
		protected function generateMethodDefinition($type, $name, array $methodData) {
			$modifier  = 'public';
			$params    = '';
			$paramList = '';
			if ($type === 'default') {
				$method    = $this->referenceObject->getMethod($name);
				$modifier  = ($method->isPublic()) ? 'public' : 'protected';
				$params    = Util::buildParameterDefinition($method);
				$paramList = Util::buildParameterNameList($method);
			}
			
			$temp1      = '$___temp1_' . uniqid();
			$temp2      = '$___temp2_' . uniqid();
			$parentCall = ($methodData['call_parent']) ? "parent::$name($paramList);" : '//placeholder for call to parent';
			$body       = (!empty($methodData['body'])) ? str_replace("\n", "\n\t\t", $methodData['body']) : '//placeholder for custom method body';
			$code       = <<<CODE
	$modifier function $name($params) {
		$temp1 = func_get_args();
		$temp2 = MockRegistry::registerInvocation(get_class(\$this), __FUNCTION__, $temp1);
		if ($temp2 instanceof InvocationExpectation) {
			//this invocation matched an invocation expectation
			
			$parentCall
			
			return {$temp2}->execute();
		}
		unset($temp1, $temp2);
		
		$parentCall
		
		$body
	}
CODE;
			
			return $code;
		}
		
	}

?>