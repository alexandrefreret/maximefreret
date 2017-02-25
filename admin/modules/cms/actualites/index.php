<?php
    require_once ($root . "objects/news.class.php");
    require_once ($root . "objects/media.class.php");
    class _module extends __module
    {

        private $limit_nb_actu = 5;
        private $news_id_encode;
        private $limit_resume = 80;


        function switchAction()
        {
            if(preg_match("#editer/(.*),([a-z0-9]+)#", $this->requete->url,$matches))
            {
                $this->news_id_encode = $matches[2];
                $this->editer();
            }
            elseif(preg_match("#save.html#", $this->requete->url,$matches))
            {
                $this->save();
            }
            elseif(preg_match("#ajouter#", $this->requete->url,$matches))
            {
                $this->ajouter();
            }
            elseif(preg_match("#supprimer/([a-z0-9]+).html#", $this->requete->url,$matches))
            {
                $this->news_id_encode = $matches[1];
                $this->delete();
            }
            elseif(preg_match("#(\/)?#", $this->requete->url,$matches))
            {
                $this->liste();
            }
        }



        function liste()
        {
            global $template,$helper;

            $this->title = "Actualités";

            $news = new News();

            $page = 1;
            if(isset($_GET["page"]) && $_GET["page"] != "" && $_GET["page"] > 0)
            {
                $page = $_GET["page"];
            }

            $from = ($page - 1) * $this->limit_nb_actu;

            $actualites = $news->get_news($from,$this->limit_nb_actu);

            $nb_actu = $news->count_news();

            if(!empty($actualites))
            {
                //Calcul de la pagination a afficher
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

                    $actualite["news_id_encode"] = $this->get_encode_id($actualite["news_id"]);
                    $actualite["news_label_url"] = $helper->clean_url($actualite["news_label"]);

                    $actualite["news_resume"] = $helper->truncate($actualite["news_contenu"], 200);
                    $template->assign_block_vars("actualites", $actualite);


                    //SI la fin de publication est différente de 00/00/0000
                    if($actualite["news_datefin_fr"] != "00/00/0000")
                    {
                        $template->assign_block_vars("actualites.fin_publication", array());
                    }


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

            $this->render("liste");
        }
        
        
        
        function editer()
        {
            global $template;

            $this->title = "Actualités";
            
            $template->assign_block_vars("js", array("src" => "plugins/datepicker/datepicker.min.js"));

            $template->assign_block_vars("js", array("src" => "plugins/editeur/trumbowyg.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/upload/trumbowyg.upload.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/base64/trumbowyg.base64.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/noembed/trumbowyg.noembed.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/pasteimage/trumbowyg.pasteimage.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/colors/trumbowyg.colors.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/preformatted/trumbowyg.preformatted.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/langs/fr.min.js"));


            $template->assign_block_vars("css", array("src" => "trumbowyg.css"));
            $template->assign_block_vars("css", array("src" => "datepicker.css"));


            $news = new News();

            $actualites = $news->get_news_id($this->news_id_encode);

            if($actualites["news_datefin_fr"] == "00/00/0000")
            {
                $actualites["news_datefin_fr"] = "";
            }

            $template->assign_vars($actualites);

            $categories = $news->get_news_categories();
            if(!empty($categories))
            {
                foreach ($categories as $key => $categorie)
                {
                    if($actualites["news_categorie"] == $categorie["categorie_id"])
                    {
                        $categorie["selected"] = "selected";
                    }

                    $template->assign_block_vars("categorie", $categorie);
                }
            }
            
            
            //Je récupere l'image d'illustration s'il y en a une 
            $media = new Media();

            $news_media = $media->get_media($actualites["news_id"], "news", true);

            if(!empty($news_media))
            {
                $template->assign_block_vars("photo", $news_media);
            }
            else
            {
                $template->assign_block_vars("no_photo", array());
            }

            $this->render("editer");
        }


        function ajouter()
        {
            global $template;
            $this->title = "Actualités";

            $template->assign_block_vars("js", array("src" => "plugins/datepicker/datepicker.min.js"));

            $template->assign_block_vars("js", array("src" => "plugins/editeur/trumbowyg.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/upload/trumbowyg.upload.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/base64/trumbowyg.base64.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/noembed/trumbowyg.noembed.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/pasteimage/trumbowyg.pasteimage.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/colors/trumbowyg.colors.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/plugins/preformatted/trumbowyg.preformatted.min.js"));
            $template->assign_block_vars("js", array("src" => "plugins/editeur/langs/fr.min.js"));


            $template->assign_block_vars("css", array("src" => "trumbowyg.css"));
            $template->assign_block_vars("css", array("src" => "datepicker.css"));


            $news = new News();

            $categories = $news->get_news_categories();
            if(!empty($categories))
            {
                foreach ($categories as $key => $categorie)
                {
                    $template->assign_block_vars("categorie", $categorie);
                }
            }

            $template->assign_block_vars("no_photo", array());
            $this->render("editer");

        }


        function save()
        {
            global $helper;

            if($_POST["news_datefin"] == "")
            {
                $_POST["news_datefin"] = "00/00/0000";
            }


            if($_POST["news_datedebut"] == "")
            {
                $_POST["news_datedebut"] = date("d/m/Y");
            }

            //Update
            $tab = array(
                "news_label" => $_POST["news_label"],
                "news_categorie" => $_POST["news_categorie"],
                "news_contenu" => $_POST["news_contenu"],
                "news_datedebut" => $helper->date2en($_POST["news_datedebut"]),
                "news_datefin" => $helper->date2en($_POST["news_datefin"]),
            );

            if(isset($_POST['news_id_encode']) && $_POST["news_id_encode"] != "")
            {

                $restrict = array(
                    "SUBSTR(SHA1(news_id),1,13)" => $_POST["news_id_encode"]
                );

                $this->requete->update("news", $tab, $restrict);
            }
            else
            {
                //Insert
                $this->requete->insert("news", $tab);
            }

            if(!$this->requete->is_ajax())
            {
                $this->requete->redirect(WEBROOT . "admin/cms/actualites/");
            }
        }



        function delete()
        {
            $news = new News();

            $news->delete($this->news_id_encode);

            $this->requete->redirect(WEBROOT . "admin/cms/actualites/");
        }
    }