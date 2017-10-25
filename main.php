<?php

	session_start();
	require_once('vendor/autoload.php');


	require_once 'src/mf/utils/ClassLoader.php';
	$loader = new mf\utils\ClassLoader('src');
	$loader->register(); 

	$config = parse_ini_file('conf/config.ini');

	$db = new Illuminate\Database\Capsule\Manager();

	$db->addConnection( $config );
	$db->setAsGlobal();
	$db->bootEloquent();




	  $router = new mf\router\Router();

	  $router->addRoute('maison',
	                    '/home/',
	                    '\tweeterapp\control\TweeterController',
	                    'viewHome');

	  /* Après exécution de l'instruction l'attribut $routes de la classe ``Router``aura la valeur : */



	$router->addRoute('home',    '/home/',         '\tweeterapp\control\TweeterController', 'viewHome', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
	$router->addRoute('view',    '/view/',         '\tweeterapp\control\TweeterController', 'viewTweet', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
	$router->addRoute('user',    '/user/',         '\tweeterapp\control\TweeterController', 'viewUserTweets', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
	$router->addRoute('signup',    '/signup/',         '\tweeterapp\control\TweeterController', 'signUp', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
	$router->addRoute('check_signup',    '/check_signup/',         '\tweeterapp\control\TweeterController', 'checkSignup', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
	$router->addRoute('login',    '/login/',         '\tweeterapp\control\TweeterController', 'login', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
	$router->addRoute('check_login',    '/check_login/',         '\tweeterapp\control\TweeterController', 'checkLogin', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
	$router->addRoute('logout',    '/logout/',         '\tweeterapp\control\TweeterController', 'logout', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
	$router->addRoute('following',    '/following/',         '\tweeterapp\control\TweeterController', 'following', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
	$router->addRoute('post',    '/post/',         '\tweeterapp\control\TweeterController', 'post', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
	$router->addRoute('send',    '/send/',         '\tweeterapp\control\TweeterController', 'send', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
	$router->addRoute('follow',    '/follow/',         '\tweeterapp\control\TweeterController', 'follow', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
	$router->addRoute('like',    '/like/',         '\tweeterapp\control\TweeterController', 'like', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
	$router->addRoute('dislike',    '/dislike/',         '\tweeterapp\control\TweeterController', 'dislike', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);


	$router->addRoute('default', 'DEFAULT_ROUTE',  '\tweeterapp\control\TweeterController', 'viewHome');

	$router->run();