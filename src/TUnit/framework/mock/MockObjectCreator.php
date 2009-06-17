<?php

	interface MockObject {}

	class MockObjectCreator {
		
		protected $referenceObject;
		protected $methods;
		
		protected static $registry = array();
		protected static $expectations = array();
		
		public function __construct($class, $callParent = true) {
			if (!class_exists($class) && !interface_exists($class)) {
				throw new InvalidArgumentException('The class "' . $class . '" does not exist');
			}
			
			$refClass = new ReflectionClass($class);
			
			if ($refClass->isFinal()) {
				throw new LogicException('The class "' . $class . '" is final and cannot be mocked');
			}
			
			$this->referenceObject = $refClass;
			$this->methods         = array(
				'default' => array(
					$this->referenceObject->getConstructor()->getName() => array(
						'body'        => '',
						'call_parent' => (bool)$callParent
					)
				),
				'generic' => array()
			);
		}
		
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
		
		protected function methodIsMockable(ReflectionMethod $method) {
			return !$method->isFinal() && !$method->isPrivate() && !$method->isStatic();
		}
		
		public function generate(array $constructorArgs = array(), $name = '') {
			if (empty($name)) {
				$className = $this->referenceObject->getName();
				do {
					$name = 'Mock_' . $className . '_' . uniqid();
				} while (class_exists($name) || interface_exists($name));
			}
			
			if (class_exists($name) || interface_exists($name)) {
				throw new RuntimeException('Cannot use the name "' . $name . '" for mock object because the class or interface already exists');
			}
			
			$code = $this->generateClassDefinition($name);
			eval($code);
			
			MockRegistry::addClass($name);
			
			$obj = new ReflectionClass($name);
			$obj = $obj->newInstanceArgs($constructorArgs);
			return $obj;
		}
		
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