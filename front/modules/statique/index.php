<?php
    require_once (ROOT . "objects/page.class.php");

    class _module extends __module
    {

        function switchAction()
        {
            //Page d'accueil du topic
            if(preg_match("#(.*).html#", $this->requete->url,$matches))
            {
                $this->page();
            }
            elseif(preg_match("#\/#", $this->requete->url,$matches))
            {
                $this->index();
            }

        }

        function index()
        {

            $this->render("index");
        }

        
        function page()
        {
            global $template;
            
            //Je vais rechercher la page correspondant a mon url
            $page = new Page();

            $post = $page->get_page_site($this->requete->url);
            if(!empty($post))
            {
                $template->assign_vars($post);
                $this->render("page");
            }
            else
            {
                //Erreur 404
                $this->requete->erreur_404($this);
            }
        }
        
        
    }