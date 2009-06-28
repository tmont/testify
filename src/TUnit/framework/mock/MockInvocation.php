<?php

	/**
	 * MockInvocation
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Struct representing an invocation of mocked method
	 *
	 * @package TUnit
	 * @author  Tommy Montgomery
	 * @version 1.0
	 * @since   1.0
	 */
	class MockInvocation {
		
		/**
		 * The name of the mocked class on which the method was invoked
		 *
		 * @var string
		 */
		protected $className;
		
		/**
		 * Name of the invoked method
		 *
		 * @var string
		 */
		protected $method;
		
		/**
		 * The arguments passed to the method upon invocation
		 *
		 * @var array
		 */
		protected $args;
		
		/**
		 * The number of times this method was invoked
		 *
		 * @var int
		 */
		protected $count;
		
		/**
		 * Constructor
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @param  string $className
		 * @param  string $methodName
		 * @param  array  $args
		 * @param  int    $count
		 */
		public function __construct($className, $methodName, array $args, $count) {
			$this->className = $className;
			$this->method    = $methodName;
			$this->args      = $args;
			$this->count     = $count;
		}
		
		/**
		 * Gets the name of the method
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return string
		 */
		public function getMethod() {
			return $this->method;
		}
		
		/**
		 * Gets the arguments
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return array
		 */
		public function getArgs() {
			return $this->args;
		}
		
		/**
		 * Gets the name of the class
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return mixed
		 */
		public function getClass() {
			return $this->className;
		}
		
		/**
		 * Gets the count
		 *
		 * @author  Tommy Montgomery
		 * @version 1.0
		 * @since   1.0
		 * 
		 * @return mixed
		 */
		public function getCount() {
			return $this->count;
		}
		
	}

?>