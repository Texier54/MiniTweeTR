<?php
	namespace mf\utils;
	class ClassLoader
	{

		private $prefix;
		
		public function __construct($prefix)
		{
			$this->prefix = $prefix;
		}

		public function autoload($class_name)
		{
		    $class_name = $this->prefix."/".str_replace("\\",'/',$class_name).".php";
		    if(file_exists($class_name))
				require_once($class_name);
		}

		public function register()
		{
			spl_autoload_register(array($this,'autoload'));
		}

	
	}
