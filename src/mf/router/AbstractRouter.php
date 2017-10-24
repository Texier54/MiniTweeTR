<?php

namespace mf\router;

abstract class AbstractRouter {

    protected $http_req = null;

    
    /*
     * Attribut statique qui stocke les routes possibles de l'application 
     * 
     * - Une route est reprÃ©sentÃ©e par un tableau :
     *       [ le controlleur, la methode ]
     * 
     */
    
    static public $routes = array ();



    public function __construct(){
        $this->http_req = new \mf\utils\HttpRequest();
    }

    
    /* 
     * MÃ©thode addRoute : ajoute une route a la liste des routes 
     *
     * ParamÃ¨tres :
     * 
     * - $name (String) : un nom pour la route
     * - $url (String)  : l'url de la route
     * - $ctrl (String) : le nom de la classe du ContrÃ´leur 
     * - $mth (String)  : le nom de la mÃ©thode qui rÃ©alise la fonctionalitÃ© 
     *                     de la route
     * 
     * Algorithme :
     *
     * - Ajouter le tablau [ $ctrl, $mth ] au tableau $this->route 
     *   sous la clÃ© $url et sous la clÃ© $name 
     *
     */


    abstract public function addRoute($name, $url, $ctrl, $mth);

    /*
     * MÃ©thode run : execute une route en fonction de la requÃªte 
     *
     *
     * Algorithme :
     * 
     * - l'URL de la route est stockÃ©e dans l'attribut $path_info de 
     *         $http_request
     *   Et si une route existe dans le tableau $route sous le nom $path_info
     *     - crÃ©er une instance du controleur de la route
     *     - exÃ©cuter la mÃ©thode de la route 
     * - Sinon 
     *     - exÃ©cuter la route par dÃ©faut : 
     *        - crÃ©er une instance du controleur de la route par dÃ©fault
     *        - exÃ©cuter la mÃ©thode de la route par dÃ©fault
     * 
     */
    
    abstract public function run();

}
    
   

