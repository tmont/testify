<?php

	interface MockObject {}

	class MockObjectCreator {
		
		protected $referenceObject;
		protected $methods;
		
		public function __construct($class) {
			if (!class_exists($class) || !interface_exists($class)) {
				throw new InvalidArgumentException('The class "' . $class . '" does not exist');
			}
			
			$refClass = new ReflectionClass($class);
			
			if ($refClass->isFinal()) {
				throw new LogicException('The class "' . $class . '" is final and cannot be mocked');
			}
			
			$this->referenceObject = $refClass;
			$this->methods = array(
				'default' => array(),
				'generic' => array()
			);
		}
		
		public function addMethod($methodName, $callParent = false, $body = '') {
			$methodType = 'generic';
			
			if ($this->referenceObject->hasMethod($methodName)) {
				$method = $this->referenceObject->getMethod($methodName);
				if (!$this->methodIsMockable($method))
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
		
		protected function getDefaultMethodBody() {
			
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
		}
		
		private function generateClassDefinition(ReflectionClass $class, array $methods, array $args, $name) {
			$code = 'class ' . $name . ' ' . ($class->isInterface() ? 'implements' : 'extends') . $class->getName() . ' {' . "\n";
			
			$genericMethods = array();
			foreach ($methods as $method) {
				if ($class->hasMethod($method)) {
					$code .= self::generateMethodDefinition($class->getMethod($method));
				}
			}
			
			$code .= '}';
			return $code;
		}
		
		private function generateMethodDefinition(ReflectionMethod $method) {
			
		}
		
		private function generateGenericMethodDefinition(array $methods) {
			$code  = 'public function __call($method, array $args) {' . "\n";
			$code .= '	$allowedMethods = ' . var_export($methods, true) . ';' . "\n";
			$code .= '	if (in_array($method, $allowedMethods)) {' . "\n";
			$code .= '		' . "\n";
			$code .= '	} else {' . "\n";
			$code .= '		throw new BadMethodCallException(\'The method "\' . $method . \'" does not exist on the mocked class "\' . get_class($this) . \'"\');' . "\n";
			$code .= '	}' . "\n";
			$code .= '}';
			
			return $code;
		}
		
	}

?>