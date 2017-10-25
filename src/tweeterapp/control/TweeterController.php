<?php

namespace tweeterapp\control;



/* Classe TweeterController :
 *  
 * Réalise les algorithmes des fonctionnalités suivantes: 
 *
 *  - afficher la liste des Tweets 
 *  - afficher un Tweet
 *  - afficher les tweet d'un utilisateur 
 *  - afficher la le formulaire pour poster un Tweet
 *  - afficher la liste des utilisateurs suivis 
 *  - évaluer un Tweet
 *  - suivre un utilisateur
 *   
 */

class TweeterController extends \mf\control\AbstractController {


    /* Constructeur :
     * 
     * Appelle le constructeur parent
     *
     * c.f. la classe \mf\control\AbstractController
     * 
     */
    
    public function __construct(){
        parent::__construct();
    }


    /* MÃ©thode viewHome : 
     * 
     * RÃ©alise la fonctionnalitÃ© : afficher la liste de Tweet
     * 
     */
    
    public function viewHome(){

        if(isset($this->request->get['page']))
            $decal = $this->request->get['page'];
        else
            $decal = 0;

        $ctrl=[];
        $requete = \tweeterapp\model\Tweet::select();  // SQL : select * from 'ville'
        $lignes = $requete  ->orderByDESC('created_at', 'DESC')
                            ->limit(5)
                            ->skip($decal*5)
                            ->get();   // exécution de la requête et plusieurs lignesrésultat

        foreach ($lignes as $v)       // $v est une instance de la classe Ville
        {
            $user = $v->user()->first();

            $tab = [$v->id, $v->text, $v->created_at, $v->author, $user['fullname']];
            $ctrl[] = $tab;
        }


        $v = new \tweeterapp\view\TweeterView($ctrl); /* Contient une liste de Tweets*/
        $v ->render('home'); /* Le sélecteur est la chaîne home */

    }


    /* MÃ©thode viewTweet : 
     *  
     * RÃ©alise la fonctionnalitÃ© afficher un Tweet
     *
     */
    
    public function viewTweet(){

        if($this->request->get['id']==null)
            echo 'error';
        else
        {
            $requete = \tweeterapp\model\Tweet::select()
                                ->where('id', '=', $this->request->get['id'])
                                ->get();

            if($requete!='[]')
            {
                $v = new \tweeterapp\view\TweeterView($requete); /* Contient une liste de Tweets*/
                $v ->render('viewTweet'); /* Le sélecteur est la chaîne home */
            }
            else
                echo 'error';
        }
        
    }


    /* MÃ©thode viewUserTweets :
     *
     * RÃ©alise la fonctionnalitÃ© afficher les tweet d'un utilisateur
     *
     */
    
    public function viewUserTweets(){

        if($this->request->get['id']==null)
            echo 'error';
        else
        {
            $ctrl=null;

            $v = \tweeterapp\model\User::where('id', '=', $this->request->get['id'])->first();
            $d = \tweeterapp\model\User::where('id', '=', $this->request->get['id'])->get();
            if($v!=null)
            {
                $liste = $v->tweets()->get();

                foreach ($liste as $v)
                {
                   $tab = [$v->text, $v->created_at, $d[0]['username'], $v->id];
                   $ctrl[] = $tab;
                }

                $v = new \tweeterapp\view\TweeterView($ctrl); /* Contient une liste de Tweets*/
                $v ->render('userTweets'); /* Le sélecteur est la chaîne home */
            }
            else
                echo 'error';
        }

    }

    public function signUp(){

        $v = new \tweeterapp\view\TweeterView(null);
        $v ->render('signUp'); /* Le sélecteur est la chaîne home */

    }

    public function checkSignup(){

        $username = $this->request->post['username'];
        $pass = $this->request->post['password'];
        $fullname = $this->request->post['fullname'];

        $v = new \tweeterapp\auth\TweeterAuthentification();
        try {
            $v->createUser($username, $pass, $fullname);
            $this->viewHome();
        }
        catch(\mf\auth\exception\AuthentificationException $e)
        {
            $this->signUp();
        }

    }

    public function login(){

        $v = new \tweeterapp\view\TweeterView(null);
        $v ->render('login'); /* Le sélecteur est la chaîne home */

    }

    public function checkLogin(){
        
        $username = $this->request->post['username'];
        $password = $this->request->post['password'];

        $v = new \tweeterapp\auth\TweeterAuthentification();
        try {
            $v->login($username, $password);
            $this->viewHome();
        }
        catch(\mf\auth\exception\AuthentificationException $e)
        {
            echo $e->getMessage();
            $this->login();
        }

    }

    public function logout(){
        $v = new \tweeterapp\auth\TweeterAuthentification();
        $v->logout();
        $this->viewHome();
    }

    public function post(){

        $v = new \tweeterapp\view\TweeterView(null);
        $v ->render('post'); /* Le sélecteur est la chaîne home */

    }

    public function send(){

        $v = new \tweeterapp\auth\TweeterAuthentification();

        $requete = \tweeterapp\model\User::where('username', '=', $v->user_login);
        $user = $requete->first();

        $tweet = new \tweeterapp\model\Tweet();
        $tweet->text = htmlspecialchars(addslashes($this->request->post['text']));
        $tweet->author= $user->id;
        $tweet->save();
        $this->viewHome();
    }

    public function following() {

        $user = new \tweeterapp\auth\TweeterAuthentification();

        $requete = \tweeterapp\model\User::where('username', '=', $user->user_login);
        $userreq = $requete->first();

        $requete = \tweeterapp\model\Follow::where('follower', '=', $userreq->id);
        $follow = $requete->get();

        foreach ($follow as $key => $value) {
            $requete = \tweeterapp\model\User::where('id', '=', $value->followee);
            $followee[] = $requete->first();   
        }

        $v = new \tweeterapp\view\TweeterView($followee);
        $v ->render('following'); /* Le sélecteur est la chaîne home */

    }

    public function follow(){

        $user = new \tweeterapp\auth\TweeterAuthentification();

        $requete = \tweeterapp\model\User::where('username', '=', $user->user_login);
        $userreq = $requete->first();

        $v = new \tweeterapp\model\Follow();
        $v->follower = $userreq->id;
        $v->followee = $this->request->get['id'];
        $v->save();

        $requete = \tweeterapp\model\User::where('id', '=', $this->request->get['id']);
        $user = $requete->first(); 

        $user->follow = $tweet->follow+1;
        $user->save();

        $this->following();
    }

    public function like(){

        $user = new \tweeterapp\auth\TweeterAuthentification();

        $requete = \tweeterapp\model\User::where('username', '=', $user->user_login);
        $userreq = $requete->first();

        $iflike = $userreq->likeIDo()->where('tweet_id', '=', $this->request->get['id'])->first();

        if($iflike==null)
        {

            $like = new \tweeterapp\model\Like();

            $like->user_id = $userreq->id;
            $like->tweet_id = $this->request->get['id'];
            $like->save();

            $requete = \tweeterapp\model\Tweet::where('id', '=', $this->request->get['id']);
            $tweet = $requete->first(); 

            $tweet->score = $tweet->score+1;
            $tweet->save();

        }

        $this->viewTweet();
    }

    public function dislike(){

        $user = new \tweeterapp\auth\TweeterAuthentification();

        $requete = \tweeterapp\model\User::where('username', '=', $user->user_login);
        $userreq = $requete->first();

        $iflike = \tweeterapp\model\Like::where('tweet_id' ,'=', $this->request->get['id'])->where('user_id', '=', $userreq->id)->first();

        if($iflike!=null)
        {

            $iflike->delete();

            $requete = \tweeterapp\model\Tweet::where('id', '=', $this->request->get['id']);
            $tweet = $requete->first(); 

            $tweet->score = $tweet->score-1;
            $tweet->save();

        }

        $this->viewTweet();
    }

}
