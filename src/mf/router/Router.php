<?php

	namespace mf\router;

	class Router extends AbstractRouter{


		    public function __construct(){
        	parent::__construct();
    	}

		public function addRoute($name, $url, $ctrl, $mth, $level=0) {
			$tab[0]=$ctrl;
			$tab[1]=$mth;
			$tab[2]=$level;

			self::$routes[$url]=$tab;
			self::$routes[$name]=$tab;

		}

		public function run() {
		    if(isset(self::$routes[$this->http_req->path_info]))
		    {	
		    	$v = new \tweeterapp\auth\TweeterAuthentification();
		    	if($v->checkAccessRight(self::$routes[$this->http_req->path_info][2]))
		    	{
			    	$class = self::$routes[$this->http_req->path_info][0];
			    	$test = new $class();
			    	$method = self::$routes[$this->http_req->path_info][1];
			    	$test-> $method();
			    }
			    else
			    {
		    		$test = new \tweeterapp\control\TweeterController();
		    		$test->viewHome();
			    }
		    }
		    else
		    {
		    	$test = new \tweeterapp\control\TweeterController();
		    	$test->viewHome();
		    }
		}

	}
