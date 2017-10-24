<?php
	namespace mf\utils;

	require_once("AbstractHttpRequest.php");

	class HttpRequest extends AbstractHttpRequest
	{

		public function __construct()
		{
			$this->script_name = $_SERVER['SCRIPT_NAME'];
			$this->method = $_SERVER['REQUEST_METHOD'];
			if(isset($_SERVER['PATH_INFO']))
				$this->path_info = $_SERVER['PATH_INFO'];
			$this->root = dirname($_SERVER['SCRIPT_NAME']);
			$this->get = $_GET;
			$this->post= $_POST;
		}

	}
