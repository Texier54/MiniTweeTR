<?php

namespace tweeterapp\view;

class TweeterView extends \mf\view\AbstractView {
  
    /* Constructeur 
    *
    * Appelle le constructeur de la classe \mf\view\AbstractView
    */
    public function __construct( $data ){
        parent::__construct($data);
    }

    /* MÃ©thode renderHeader
     *
     *  Retourne le fragment HTML de l'entÃªte (unique pour toutes les vues)
     */ 
    private function renderHeader(){
        return '<h1>MiniTweeTR</h1>';
    }
    
    /* MÃ©thode renderFooter
     *
     * Retourne  le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    private function renderFooter(){
        return 'La super app créée en Licence Pro &copy;2017';
    }

    private function renderMenu(){

        $racine =  $this->app_root;

        $v = new \tweeterapp\auth\TweeterAuthentification();
        if($v->logged_in == true)
        {
            $retour = <<<EOT
            <nav id="navbar"><a class="tweet-control" href="${racine}/main.php/home/"><img alt="home" src=${racine}/html/home.png></a><a class="tweet-control" href="${racine}/main.php/following/"><img alt="logout" src=${racine}/html/followees.png></a><a class="tweet-control" href="${racine}/main.php/logout/"><img alt="logout" src=${racine}/html/logout.png></a></nav>

EOT;
        }
        else
        {
            $retour = <<<EOT
            <nav id="navbar"><a class="tweet-control" href="${racine}/main.php/home/"><img alt="home" src=${racine}/html/home.png></a><a class="tweet-control" href="${racine}/main.php/login/"><img alt="login" src=${racine}/html/login.png></a><a class="tweet-control" href="${racine}/main.php/signup/"><img alt="signup" src=${racine}/html/signup.png></a></nav>

EOT;
        }
        return $retour;
    }

    private function renderMenuBas(){

        $retour = '';

        $racine =  $this->app_root;

        $v = new \tweeterapp\auth\TweeterAuthentification();
        if($v->logged_in == true)
        {
            $retour = <<<EOT
            <nav id="menu" class="theme-backcolor1"> <div id="nav-menu"><div class="button theme-backcolor2"><a href="${racine}/main.php/post/">New</a></div></div> </nav>
EOT;

        }

        return $retour;
    }

    private function renderNavBas(){

        $retour = '';

        $req = new \mf\utils\HttpRequest();
        if(isset($req->get['page']))
            $page = $req->get['page'];
        else
            $page = 0;

        $prev = $page-1;
        $next = $page+1;

        $racine =  $this->app_root;

        if($page == 0)
        $retour = <<<EOT
        <div class="pager"><div id="page-prev"></div><a id="page-next" href="${racine}/main.php/home/?page=${next}">Next</a>
EOT;
        else
        $retour = <<<EOT
        <div class="pager"><a id="page-prev" href="${racine}/main.php/home/?page=${prev}">Prev</a><a id="page-next" href="${racine}/main.php/home/?page=${next}">Next</a>
EOT;

        return $retour;
    }

    /* MÃ©thode renderHome
     *
     * Retourne le fragment HTML qui rÃ©alise la fonctionalitÃ© afficher
     * tout les Tweets. 
     *  
     * L'attribut $this->data contient un tableau d'objets tweet.
     * 
     */
    
    private function renderHome(){  

        $retour = '';
        foreach ($this->data as $value) {
           $retour = $retour.'  
            <div class="tweet">
                <a class="tweet-text" href="'.$this->app_root.'/main.php/view/?id='.$value[0].'">'.$value[1].'</a>
                    <div class="tweet-footer">
                        <span class="tweet-timestamp">'.$value[2].'</span>
                        <span class="tweet-author"><a href="'.$this->app_root.'/main.php/user/?id='.$value[3].'">'.$value[4].'</a></span>
                    </div>
            </div>';
        }
        return '<h2>Latest Tweets</h2>'.$retour.$this->renderNavBas();


    }
    
    /* Méthode renderUeserTweets
     *
     * Retourne le fragment HTML qui réalise la fonctionalité afficher
     * tout les Tweets d'un utilisateur donné. 
     *  
     * L'attribut $this->data contient un objet User.
     * 
     */
     
    private function renderUserTweets(){

        $retour = '';
        foreach ($this->data as $value) {
           $retour = $retour.'  
            <div class="tweet">
                <a class="tweet-text" href="'.$this->app_root.'/main.php/view/?id='.$value[3].'">'.$value[0].'</a>
                    <div class="tweet-footer">
                        <span class="tweet-timestamp">'.$value[1].'</span>
                        <span class="tweet-author"><a href="'.$this->app_root.'/main.php/view/?id=">'.$value[2].'</a></span>
                    </div>
            </div>';
        }
            
            $v = \tweeterapp\model\Tweet::where('id', '=', $value[3])->first();

            $author = $v->user()->first()['username'];

        return '<h2>'.$author.'</h2>'.$retour;


    }

    private function renderViewTweet(){

            $id = $this->data[0]['id'];
            $id_author = $this->data[0]['author'];
            $text = $this->data[0]['text'];
            $date = $this->data[0]['created_at'];
            $like = $this->data[0]['score'];
            $racine =  $this->app_root;

            $v = \tweeterapp\model\Tweet::where('id', '=', $id)->first();

            $author = $v->user()->first()['username'];

            $retour = <<<EOT
            <div class="tweet">
                <a  class="tweet-text" href="">${text}</a>
                    <div class="tweet-footer">
                        <span class="tweet-timestamp">${date}</span>
                        <span class="tweet-author"><a href="${racine}/main.php/user/?id=${id_author}">${author}</a></span>
                    </div>
                    <div class="tweet-footer">
                        <hr>
                        <span class="tweet-score tweet-control">${like}</span>

EOT;
            $user = new \tweeterapp\auth\TweeterAuthentification();

            if($author!=$user->user_login) 
                $retour .=<<<EOT
                        <a class="tweet-control" href="${racine}/main.php/like/?id=${id}"><img alt="Like" src=${racine}/html/like.png></a>
                        <a class="tweet-control" href="${racine}/main.php/dislike/?id=${id}"><img alt="dislike" src=${racine}/html/dislike.png></a>
                        <a class="tweet-control" href="${racine}/main.php/follow/?id=${id_author}"><img alt="Follow" src=${racine}/html/follow.png></a>

EOT;

    $retour .=<<<EOT
                    </div>
            </div>
EOT;

        return $retour;

    }

    private function renderSignUp() {

        $retour = '
        <form class="forms" action="'.$this->app_root.'/main.php/check_signup/" method=post>
            <input class="forms-text" type=text name=fullname placeholder="full Name">
            <input class="forms-text" type=text name=username placeholder="username">
            <input class="forms-text" type=password name=password placeholder="password">
            <input class="forms-text" type=password name=password_verify placeholder="retype password">

            <button class="forms-button" name=login_button type="submit">Create</button>
        </form> ';
        return $retour;

    }

    private function renderLogin() {

        $retour = '
        <form class="forms" action="'.$this->app_root.'/main.php/check_login/" method=post>
            <input class="forms-text" type=text name=username placeholder="username">
            <input class="forms-text" type=password name=password placeholder="password">
            <button class="forms-button" name=login_button type="submit">Login</button>
        </form>';

        return $retour;
    }

    private function renderFollowing() {

        $racine =  $this->app_root;

        $retour = '<ul id="followees">';
        foreach ($this->data as $value) {
           $retour = $retour.'  
            
                <li><a href="'.$racine.'/main.php/user/?id='.$value->id.'">'.$value->username.'</a>
                </li>
            ';
        }

        return '<h2>Currently following</h2>'.$retour.'</ul>';
    }

    private function renderPost() {

        $racine =  $this->app_root;

        $retour = <<<EOT
        <form action="${racine}/main.php/send/" method=post>

            <textarea id="tweet-form" name=text placeholder="Enter tweet...", maxlength=140></textarea>
            <div><input id="send_button" type=submit name=send value="Send"></div>
        </form>

EOT;

        return $retour;
    }

    private function renderSend() {

        $retour = '';

        return $retour;
    }
    
    protected function renderBody($selector=null){
      
        $header = $this->renderHeader();
        $footer = $this->renderFooter();
        $menu = $this->renderMenu();
        $menubas = $this->renderMenuBas();

        switch ($selector) {
            case "home":
                $contenu = $this->renderHome();
                break;
            case "viewTweet":
                $contenu = $this->renderViewTweet();
                break;
            case "userTweets":
                $contenu = $this->renderUserTweets();
                break;
            case "signUp":
                $contenu = $this->renderSignUp();
                break;
            case "login":
                $contenu = $this->renderLogin();
                break;
            case "following":
                $contenu = $this->renderFollowing();
                break;
            case "post":
                $contenu = $this->renderPost();
                break;
        }

        $racine =  $this->app_root;

        $html = <<<EOT
            <header class="theme-backcolor1">
                ${header}
                ${menu}
            </header>

            <section class="theme-backcolor2">
                <article>
                    ${contenu}
                </article>
                ${menubas}
            </section>


            <footer class="theme-backcolor1">
                ${footer}
            </footer>

EOT;

        return  $html;
        
    }

}

