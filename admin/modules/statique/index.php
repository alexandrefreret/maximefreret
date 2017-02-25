<?php
    require_once ($root . "objects/param.class.php");
    require_once (ROOT . "objects/page.class.php");


    class _module extends __module
    {

        function switchAction()
        {
            //Page d'accueil du topic
            if(preg_match("#cms/page(\/)?$#", $this->requete->url,$matches))
            {
                $this->liste_page();
            }
            elseif(preg_match("#\/#", $this->requete->url,$matches))
            {
                $this->index();
            }

        }

        function index()
        {
            global $template;
            $this->title = "Administration";

            //Je récupere les params
            $param = new Param();

            $params = $param->get_params();

            if(!empty($params))
            {
                foreach ($params as $param)
                {
                    $template->assign_var($param["param_label"], $param["param_value"]);
                }
            }
            $this->render("index");
        }


        function liste_page()
        {
            global $template, $helper;

            $this->title = "Pages statique";
            
            $page = new Page();
            
            $posts = $page->get_pages();
            
            if(!empty($posts))
            {
                foreach ($posts as $post)
                {
                    $post["page_resume"] = $helper->truncate($post["page_contenu"],250);
                    $template->assign_block_vars("page", $post);
                }
            }

            $this->render("liste_page");
        }
    }