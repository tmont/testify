<?php

	interface MockObject {}

	class MockObjectCreator {
		
		protected $referenceObject;
		protected $methods;
		
		protected static $registry = array();
		
		public function __construct($class) {
			if (!class_exists($class) || !interface_exists($class)) {
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
						'call_parent' => true
					)
				),
				'generic' => array()
			);
		}
		
		public static function registerInvocation($name, MockInvocation $invocation) {
			if (!isset(self::$registry[$name])) {
				throw new LogicException('Unable to register invocation because the object does not exist in the registry');
			}
			
			self::$registry[$name]->registerInvocation($invocation);
		}
		
		public static function getTracker($name) {
			if (!isset(self::$registry[$name])) {
				throw new LogicException('Unable to retrieve invocation tracker because the object does not exist in the registry');
			}
			
			return self::$registry[$name];
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
			
			$obj = new ReflectionClass($name);
			$obj = $obj->newInstanceArgs($constructorArgs);
			self::$registry[$name] = new InvocationTracker();
			return $obj;
		}
		
		protected function generateClassDefinition($name) {
			$code = 'class ' . $name . ' ';
			if ($this->referenceObject->isInterface()) {
				$code .= 'implements ' . $this->referenceObject->getName() . ', MockObject';
			} else {
				$code .= 'extends ' . $this->getReferenceObject->getName() . ' implements MockObject';
			}
			
			$code .= " {\n";
			
			foreach ($this->methods as $type => $methods) {
				foreach ($methods as $method => $methodData) {
					$code .= $this->generateMethodDefinition($type, $method, $methodData);
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
				$params    = Util::buildParameterList($method);
				$paramList = Util::getParameterNameList($method);
			}
			
			$code  = "	$modifier function $name($params) {\n";
			$code .= "		MockObject::registerInvocation(get_class($this), __FUNCTION__, func_get_args());\n";
			
			if ($methodData['call_parent']) {
				$code .= "		parent::$name($paramList);\n";
			}
			if (!empty($methodData['body'])) {
				$code .= "\t\t" . str_replace("\n", "\n\t\t", $methodData['body']) . "\n";
			}
			
			$code .= '	}';
			return $code;
		}
		
	}

?>