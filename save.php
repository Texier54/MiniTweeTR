<?php
	$config = [
	       'driver'    => 'mysql',
	       'host'      => 'localhost',
	       'database'  => 'tweeter',
	       'username'  => 'cisiie',
	       'password'  => 'cisiie17',
	       'charset'   => 'utf8',
	       'collation' => 'utf8_unicode_ci',
	       'prefix'    => '' ];

	$db = new Illuminate\Database\Capsule\Manager();

	$db->addConnection( $config );
	$db->setAsGlobal();
	$db->bootEloquent();


	$requete = tweeterapp\model\User::select();  // SQL : select * from 'ville'
	$lignes = $requete->get();   // exécution de la requête et plusieurs lignesrésultat

	foreach ($lignes as $v)       // $v est une instance de la classe Ville
	       echo "Identifiant = $v->id, Nom = $v->username\n<br>";

	   echo '<br><br>';

	$requete = tweeterapp\model\Tweet::select();  // SQL : select * from 'ville'
	$lignes = $requete  ->orderBy('created_at')
						->get();   // exécution de la requête et plusieurs lignesrésultat

	foreach ($lignes as $v)       // $v est une instance de la classe Ville
	       echo "$v->text <br> Autheur $v->author\n<br><br>";



	   echo '<br><br>';

	$requete = tweeterapp\model\Tweet::select();  // SQL : select * from 'ville'
	$lignes = $requete  ->orderBy('created_at')
						->where('score', '>', '0')
						->get();   // exécution de la requête et plusieurs lignesrésultat

	foreach ($lignes as $v)       // $v est une instance de la classe Ville
	       echo "Identifiant = $v->id, Text = $v->text\n Score = $v->score <br>";


/*
$v = new tweeterapp\model\User();
$v->fullname = 'Test';
$v->username = 'Test';
$v->password = 'Test';
$v->level = 100;
$v->followers = 0;
$v->save();

*/