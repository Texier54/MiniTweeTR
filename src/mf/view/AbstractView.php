<?php

namespace mf\view;

abstract class AbstractView {

    static protected $style_sheets = ['/html/style.css']; /* un tableau de fichiers style */
    static protected $app_title    = "MiniTweeTR"; /* un titre de document */
    
    protected $app_root    = null; /* répertoire racine de l'application */
    protected $script_name = null; /* le chemin vers le script principal */
    
    protected $data        = null; /* le modéle de données nécessaire */
    
    /* Constructeur 
     * 
     * - Recupérer le répertoire racine de l'application et le nom du
     *   script principal depuis une instance de HttpRequest. Ces
     *   donnÃ©es serviront pour construire les URL des liens et des
     *   actions des formulaires. 
     * 
     * - Stock les donnÃ©es passÃ©es en paramÃ¨tre dans l'attribut $data.
     *   Ces donnÃ©es sont une instance du modÃ¨le ou un tableau
     *   d'instances de modÃ¨les.
     *
     */
    public function __construct( $data ){
        $http = new \mf\utils\HttpRequest();
        
        $this->script_name  = $http->script_name;
        $this->app_root     = $http->root;

        $this->data         = $data;
    }
    
    /* pour stocker des chemins vers des feuille de sytles */
    static public function setStyleSheet(array $list_of_files){
        self::$style_sheets = $list_of_files;
    }

    /* pour stocker le titre du document HTML */
    static public function setAppTitle($title){
        self::$app_title = $title;
    }

    /* La methode renderBody 
     * 
     * Cette mÃ©thode retourne le contenu HTML de la balise body autrement dit 
     * le contenu du document. Elle prend un sÃ©lecteur en paramÃ¨tre dont la 
     * valeur indique quelle vue il faut gÃ©nÃ©rer.
     * 
     * cette mÃ©thode est a dÃ©finir dans les classes concrÃ¨tess des vues, 
     * elle est appelÃ©e depuis la methode render ci-dessous.
     * 
     */
    
    abstract protected function renderBody($selector=null);
    


    /* la mÃ©thodes render
     * 
     * cette mÃ©thode gÃ©nÃ¨re le code HTML d'une page complÃ¨te depuis le doctype 
     * jusqu'au </html>. 
     * 
     * Elle dÃ¨finit les entÃªtes HTML, le titre de la page et lie les feuilles 
     * de style. le contenu du document est rÃ©cupÃ©rÃ© depuis les mÃ©thodes 
     * renderBody des sous classe. 
     *
     * Elle utilise la syntaxe HEREDOC pour dÃ©finir un patron et
     * l'Ã©crire la chaine de caractÃ¨re de la page entiÃ¨re. Voir la
     * documentation ici:
     *
     * http://php.net/manual/fr/language.types.string.php#language.types.string.syntax.heredoc
     *
     */
    
    public function render($selector){
        /* le titre du document */
        $title = self::$app_title;

        /* les feuilles de style */
        $styles = '';
        foreach ( self::$style_sheets as $file )
            $styles .= '<link rel="stylesheet" href="'.$this->app_root.'/'.$file.'"> ';

        /* on appele la methode renderBody de la sous classe */
        $body = $this->renderBody($selector);
        

        /* construire la structure de la page 
         * 
         *  Noter l'utilisation des variables ${title} ${style} et ${body}
         * 
         */
                
        $html = <<<EOT
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>${title}</title>
	    ${styles}
    </head>

    <body>
        
       ${body}

    </body>
</html>
EOT;

        /* Affichage de la page 
         *
         * C'est la seule instruction echo dans toute l'application 
         */
        
        echo $html;
    }
    
}
