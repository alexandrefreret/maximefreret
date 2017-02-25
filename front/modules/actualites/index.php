<?php
    require_once (ROOT . "objects/news.class.php");
    
    class _module extends __module
    {

        private $limit_nb_actu = 6;
        private $news_id_encode;
        private $limit_resume = 100;
        
        function switchAction()
        {
            //Ici je fais le switch pour aller dans la bonne fonction
            if(preg_match_all("#" . $this->requete->topic_url . "\/(.*),([a-z0-9]*).html$#", $this->requete->url,$matches,PREG_SET_ORDER))
            {
                $this->news_id_encode = $matches[0][2];
                $this->fiche();
            }
            elseif(preg_match_all("#(" . $this->requete->topic_url . ")?(\/)?$#", $this->requete->url,$matches))
            {
                $this->index();
            }
            else
            {
                $this->requete->erreur_404($this);
            }

        }

        function index()
        {
            global $template;
            $news = new News($this->requete);

            $page = 1;
            if(isset($_GET["page"]) && $_GET["page"] != "" && $_GET["page"] > 0)
            {
                $page = $_GET["page"];
            }

            $from = ($page - 1) * $this->limit_nb_actu;

            $actualites = $news->get_news($from,$this->limit_nb_actu);
            if(!empty($actualites))
            {

                //Calcul de la pagination a afficher
                $nb_actu = count($actualites);
                $nb_page = round($nb_actu / $this->limit_nb_actu);

                if($nb_page > 1)
                {
                    $template->assign_block_vars("pagination", array());
                    for($i=1;$i<=$nb_page;$i++)
                    {
                        $class = "";
                        if($i == $page)
                        {
                            $class = "active";
                        }
                        $template->assign_block_vars("pagination.page", array("i"=>$i,"class"=>$class,"url"=>$this->requete->url));
                    }
                }



                foreach ($actualites as $actualite)
                {
                    $actualite["news_contenu_clean"] = substr(strip_tags($actualite["news_contenu"],"<br><br />"),0,$this->limit_resume) . "...";
                    
                    $actualite["news_id_encode"] = substr(sha1($actualite["news_id"]),0,13);
                    
                    $template->assign_block_vars("actualites", $actualite);

                    if(!empty($actualite["media"]))
                    {
                        $template->assign_block_vars("actualites.media", $actualite["media"][0]);
                    }
                    else
                    {
                        $template->assign_block_vars("actualites.no_media", array());
                    }
                }
            }
            else
            {
                $template->assign_block_vars("no_actu", array());
            }

            $this->render("index");
        }


        function fiche()
        {
            global $template;

            //Je regarde si l'id encodé correspond a l'id en base
            
            $news = new News($this->requete);
            $actualites = $news->get_news_id($this->news_id_encode);

            if(!empty($actualites))
            {
                $template->assign_vars($actualites);

                if(!empty($actualites["media"]))
                {
                    foreach ($actualites["media"] as $actualite)
                    {
                        $template->assign_block_vars("media", $actualite);
                    }
                }
                else
                {
                    $template->assign_block_vars("no_media", array());
                }
            }
            else
            {
                $this->requete->erreur_404($this);
            }

            $this->render("fiche");
        }
    }