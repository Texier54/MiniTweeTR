<?php

namespace mf\auth;

abstract class AbstractAuthentification {

    /* une constante pour le niveau le plus bas*/
    const ACCESS_LEVEL_NONE = -9999; 
  
    /* l'identifiant de l'utilisateur connecté */ 
    protected $user_login   = null;

    /* son niveau d'accés */
    protected $access_level = self::ACCESS_LEVEL_NONE; 

    /* vrai s'il est connecté */
    protected $logged_in    = false;


    /* un getter et un setter + toString*/
    public function __get($attr_name) {
        if (property_exists( __CLASS__, $attr_name))
            return $this->$attr_name;
        $emess = __CLASS__ . ": unknown member $attr_name (__get)";
        throw new \Exception($emess);
    }
    
    public function __set($attr_name, $attr_val) {
        if (property_exists( __CLASS__, $attr_name)) 
            $this->$attr_name=$attr_val; 
        else{
            $emess = __CLASS__ . ": unknown member $attr_name (__set)";
            throw new \Exception($emess);
        }
    }

    public function __toString(){
        return json_encode(get_object_vars($this));
    } 


    /* Le constructeur: 
     * 
     * Faire le lien entre la variable de session et les attributs de la calsse
     *
     *   La variables de session sont les suivante : 
     *    - $_SESSION['user_login'] 
     *    - $_SESSION['access_level'] 
     *    
     *  Algorithme :
     * 
     *  Si la variable de session 'user_login' existe 
     * 
     *     - renseigner l'attribut $this->user_login avec sa valeur 
     *     - renseigner l'attribut $this->access_level avec la valeur de 
     *       la variable de session 'access_level'
     *     - mettre l'attribut $this->logged_in a vrai
     *
     *  sinon 
     *     - mettre les valeurs null, ACCESS_LEVEL_NONE et false respectivement 
     *       dans les trois attribut.
     *
     */


    
    /* La mÃ©thode updateSession : 
     *
     * MÃ©thode pour enregistrer la connexion d'un utilisateur dans la session 
     *
     * ATTENTION : cette mÃ©thode n'est appelÃ©e uniquement quand la connexion 
     *             rÃ©ussie
     *
     * @param String : $username, le login de l'utilisateur  
     * @param String : $level, le niveau d'accÃ¨s
     *
     *  Algorithme:
     *    - renseigner l'attribut $this->user_login avec le paramÃ¨tre $username 
     *    - renseigner l'attribut $this->access_level avec $level
     *
     *    - renseigner $_SESSION['user_login']  $username
     *    - renseigner $_SESSION['access_level'] $level

     *    - mettre l'attribut $this->logged_in Ã  vrai
     *
     */
    
    abstract public function updateSession($username, $level);

     /* la mÃ©thode logout :
      * 
      * MÃ©thode pour effectuer la dÃ©connexion : 
      *
      * Algorithme :
      *
      *  - Effacer les variables $_SESSION['user_login'] et 
      *    $_SESSION['access_right']
      *  - RÃ©initialiser les attributs $this->user_login, $this->access_level
      *  - Mettre l'attribut $this->logged_in a faux
      * 
      */
    
    abstract public function logout();


    /* La mÃ©thode checkAccessRight:
     * 
     * MÃ©thode pour verifier le niveau d'accÃ¨s de l'utilisateur.
     *  
     * @param  int  : $requested, le niveau requis
     * @return bool : vrai si le niveaux requis est infÃ©rieur ou Ã©gale Ã  la 
     *                valeur du niveau de l'utilisateur 
     * 
     * Algorithme :
     *
     * Si $requested > $this->access_level  
     *     retourner faux
     * Sinon 
     *     retourner vrai
     */
    
    abstract public function checkAccessRight($requested);

    
    /* MÃ©thode hashPassword :
     *
     * MÃ©thode pour hacher un mot de passe
     *  
     * @param  string : $password, le mots de passe en clair
     * @return string : mot de passe hachÃ©
     * 
     * Algorithme : 
     *  
     *   Retourner le rÃ©sultat de la fonction password_hash
     *
     */
    
    abstract protected function hashPassword($password);

    /* La MÃ©thodes verifyPassword : 
     * 
     * MÃ©thode pour vÃ©rifier si un mot de passe est Ã©gale a un hache  
     *  
     * @param string : $password, mot de passe non hachÃ© (depuis un formulaire)
     * @param string : $hash, le mot de passe hachÃ© (depuis BD)
     * @return bool  : vrai si concordance faut sinon
     * 
     *
     * Algorithme :
     * 
     *  Retourner le rÃ©sultat de la fonction password_verify
     */
    
    abstract protected function verifyPassword($password, $hash);

    
    
}
