<?php
    require_once ($root . "objects/bandeau.class.php");
    require_once ($root . "objects/media.class.php");
    class _module extends __module
    {

        private $limit_nb_actu = 15;
        private $bandeau_id_encode;


        function switchAction()
        {
            if(preg_match("#editer/([a-z0-9]+)#", $this->requete->url,$matches))
            {
                $this->bandeau_id_encode = $matches[1];
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
                $this->bandeau_id_encode = $matches[1];
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

            $this->title = "Bandeau";

            $bandeau = new Bandeau();

            $page = 1;
            if(isset($_GET["page"]) && $_GET["page"] != "" && $_GET["page"] > 0)
            {
                $page = $_GET["page"];
            }

            $from = ($page - 1) * $this->limit_nb_actu;

            $bandeaux = $bandeau->get_bandeau($from,$this->limit_nb_actu);

            $nb_bandeau = $bandeau->count_bandeau();

            if(!empty($bandeaux))
            {
                //Calcul de la pagination a afficher
                $nb_page = round($nb_bandeau / $this->limit_nb_actu);

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



                foreach ($bandeaux as $bandeau)
                {

                    $bandeau["bandeau_id_encode"] = $this->get_encode_id($bandeau["bandeau_id"]);
                    $bandeau["bandeau_label_url"] = $helper->clean_url($bandeau["bandeau_label"]);

                    $template->assign_block_vars("bandeau", $bandeau);


                    if(!empty($bandeau["media"]))
                    {
                        $template->assign_block_vars("bandeau.media", $bandeau["media"][0]);
                    }
                    else
                    {
                        $template->assign_block_vars("bandeau.no_media", array());
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

            $this->title = "Bandeau";
            
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


            $bandeau = new Bandeau();
            $bandeaux = $bandeau->get_bandeau_id($this->bandeau_id_encode);

            $template->assign_vars($bandeaux);

            
            //Je récupere l'image d'illustration s'il y en a une 
            $media = new Media();

            $bandeau_media = $media->get_media($bandeaux["bandeau_id"], "bandeau", true);

            $template->assign_var("position_" . $bandeaux["bandeau_position"] . "_selected", "selected='selected'");
            
            if(!empty($bandeau_media))
            {
                $template->assign_block_vars("photo", $bandeau_media);
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
            $this->title = "Bandeau";

            $tab = array(
                "bandeau_label" => "",
                "bandeau_contenu" => ""
            );

            $bandeau_id = $this->requete->insert("bandeau", $tab);

            $this->bandeau_id_encode = $this->get_encode_id($bandeau_id);

            $this->requete->redirect(WEBROOT . "admin/cms/bandeau/editer/" . $this->bandeau_id_encode . ".html");
        }


        function save()
        {
            global $helper;



            //Update
            $tab = array(
                "bandeau_label" => $_POST["bandeau_label"],
                "bandeau_contenu" => $_POST["bandeau_contenu"],
                "bandeau_button_link" => $_POST["bandeau_button_link"],
                "bandeau_button_text" => $_POST["bandeau_button_text"],
                "bandeau_position" => $_POST["bandeau_position"],
                "bandeau_order" => $_POST["bandeau_order"],
            );

            if(isset($_POST['bandeau_id_encode']) && $_POST["bandeau_id_encode"] != "")
            {
                $restrict = array(
                    "SUBSTR(SHA1(bandeau_id),1,13)" => $_POST["bandeau_id_encode"]
                );

                $this->requete->update("bandeau", $tab, $restrict);
            }
            else
            {
                //Insert
                $this->requete->insert("bandeau", $tab);
            }

            if(!$this->requete->is_ajax())
            {
                $this->requete->redirect(WEBROOT . "admin/cms/bandeau/");
            }
        }



        function delete()
        {
            $bandeau = new Bandeau();

            $bandeau->delete($this->bandeau_id_encode);

            $this->requete->redirect(WEBROOT . "admin/cms/bandeau/");
        }
    }