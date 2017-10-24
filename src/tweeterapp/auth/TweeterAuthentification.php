<?php

namespace tweeterapp\auth;

class TweeterAuthentification extends \mf\auth\Authentification {

    /*
     * Classe TweeterAuthentification qui définie les méthodes qui dépendent
     * de l'application (liée à la manipulation du modéle User) 
     *
     */

    /* niveaux d'accés de TweeterApp 
     *
     * Le niveau USER correspond a un utilisateur inscrit avec un compte
     * Le niveau ADMIN est un plus haut niveau (non utilisé ici)
     * 
     * Ne pas oublier le niveau NONE un utilisateur non inscrit est hérité
     * depuis AbstractAuthentification 
     */
    const ACCESS_LEVEL_USER  = 100;   
    const ACCESS_LEVEL_ADMIN = 200;

    /* constructeur */
    public function __construct(){
        parent::__construct();
    }

    /* La méthode createUser 
     * 
     *  Permet la création d'un nouvel utilisateur de l'application
     * 
     *  
     * @param : $username : le nom d'utilisateur choisi 
     * @param : $pass : le mot de passe choisi 
     * @param : $fullname : le nom complet 
     * @param : $level : le niveaux d'accés (par dÃ©faut ACCESS_LEVEL_USER)
     * 
     * Algorithme :
     *
     *  Si un utilisateur avec le même nom d'utilisateur existe déjà  en BD
     *     - soulever une exception 
     *  Sinon      
     *     - créer un nouvel modéle User avec les valeurs en paramètre 
     *       ATTENTION : Le mot de passe ne doit pas être enregistré en clair.
     * 
     */
    
    public function createUser($username, $pass, $fullname,
                               $level=self::ACCESS_LEVEL_USER) {

        $requete = \tweeterapp\model\User::where('username', '=', $username);
        $usertest = $requete->first();
        if($usertest!=null)
        {
            throw new \mf\auth\exception\AuthentificationException('Nom déjà utilisé');
        }
        else
        {
            $user = new \tweeterapp\model\User();
            $user->username = $username;
            $user->password = $this->hashPassword($pass);
            $user->fullname = $fullname;
            $user->level = $level;
            $user->followers = 0;
            $user->save();
        }
    }

    /* La méthode login
     *  
     * permet de connecter un utilisateur qui a fourni son nom d'utilisateur 
     * et son mot de passe (depuis un formulaire de connexion)
     *
     * @param : $username : le nom d'utilisateur   
     * @param : $password : le mot de passe tapé sur le formulaire
     *
     * Algorithme :
     * 
     *  Récupérer l'utilisateur avec le nom d'utilisateur $username depuis la BD
     *  Si aucun de trouvé
     *      soulever une exception 
     *  sinon 
     *      si $password correspond au mot de passe crypté en BD 
     *          charger la session de l'utilisateur
     *      sinon soulever une exception
     *
     */
    
    public function login($username, $password) {
        
        $requete = \tweeterapp\model\User::where('username', '=', $username);
        $usertest = $requete->first();

        if($usertest==null)
        {
            throw new \mf\auth\exception\AuthentificationException('Nom déjà utilisé');
        }
        else
        {
            if($this->verifyPassword($password, $usertest->password))
            {
                $this->updateSession($username, $usertest->level);
            }
            else
            {
                throw new \mf\auth\exception\AuthentificationException('Mauvais password');
            }
        }
    }

}

