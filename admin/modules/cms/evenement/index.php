<?php
    require_once ($root . "objects/event.class.php");
    require_once ($root . "objects/media.class.php");
    class _module extends __module
    {

        private $limit_nb_actu = 5;
        private $event_id_encode;
        private $limit_resume = 80;


        function switchAction()
        {
            if(preg_match("#editer/(.*),([a-z0-9]+)#", $this->requete->url,$matches))
            {
                $this->event_id_encode = $matches[2];
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
                $this->event_id_encode = $matches[1];
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

            $this->title = "Evènements";

            $event = new Event();

            $page = 1;
            if(isset($_GET["page"]) && $_GET["page"] != "" && $_GET["page"] > 0)
            {
                $page = $_GET["page"];
            }

            $from = ($page - 1) * $this->limit_nb_actu;

            $evenements = $event->get_event($from,$this->limit_nb_actu);

            $nb_actu = $event->count_event();

            if(!empty($evenements))
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



                foreach ($evenements as $evenement)
                {
                    $evenement["event_contenu_clean"] = substr(strip_tags($evenement["event_contenu"],"<br><br />"),0,$this->limit_resume) . "...";

                    $evenement["event_id_encode"] = $this->get_encode_id($evenement["event_id"]);
                    $evenement["event_label_url"] = $helper->clean_url($evenement["event_label"]);

                    $evenement["event_resume"] = $helper->truncate($evenement["event_contenu"], 200);
                    $template->assign_block_vars("evenement", $evenement);


                    //SI la fin de publication est différente de 00/00/0000
                    if($evenement["event_datefin_fr"] != "00/00/0000")
                    {
                        $template->assign_block_vars("evenement.fin_publication", array());
                    }


                    if(!empty($evenement["media"]))
                    {
                        $template->assign_block_vars("evenement.media", $evenement["media"][0]);
                    }
                    else
                    {
                        $template->assign_block_vars("evenement.no_media", array());
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

            $this->title = "Evènements";
            
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


            $event = new Event();

            $evenements = $event->get_event_id($this->event_id_encode);

            if($evenements["event_datefin_fr"] == "00/00/0000")
            {
                $evenements["event_datefin_fr"] = "";
            }

            $template->assign_vars($evenements);

            $categories = $event->get_event_categories();
            if(!empty($categories))
            {
                foreach ($categories as $key => $categorie)
                {
                    if($evenements["event_categorie"] == $categorie["categorie_id"])
                    {
                        $categorie["selected"] = "selected";
                    }

                    $template->assign_block_vars("categorie", $categorie);
                }
            }
            
            
            //Je récupere l'image d'illustration s'il y en a une 
            $media = new Media();

            $event_media = $media->get_media($evenements["event_id"], "event", true);

            if(!empty($event_media))
            {
                $template->assign_block_vars("photo", $event_media);
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
            $this->title = "Evènements";

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


            $event = new Event();

            $categories = $event->get_event_categories();
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

            if($_POST["event_datefin"] == "")
            {
                $_POST["event_datefin"] = "00/00/0000";
            }


            if($_POST["event_datedebut"] == "")
            {
                $_POST["event_datedebut"] = date("d/m/Y");
            }

            //Update
            $tab = array(
                "event_label" => $_POST["event_label"],
                "event_categorie" => $_POST["event_categorie"],
                "event_contenu" => $_POST["event_contenu"],
                "event_datedebut" => $helper->date2en($_POST["event_datedebut"]),
                "event_datefin" => $helper->date2en($_POST["event_datefin"]),
            );

            if(isset($_POST['event_id_encode']) && $_POST["event_id_encode"] != "")
            {

                $restrict = array(
                    "SUBSTR(SHA1(event_id),1,13)" => $_POST["event_id_encode"]
                );

                $this->requete->update("event", $tab, $restrict);
            }
            else
            {
                //Insert
                $this->requete->insert("event", $tab);
            }

            if(!$this->requete->is_ajax())
            {
                $this->requete->redirect(WEBROOT . "admin/cms/evenement/");
            }
        }



        function delete()
        {
            $event = new Event();

            $event->delete($this->event_id_encode);

            $this->requete->redirect(WEBROOT . "admin/cms/evenement/");
        }
    }