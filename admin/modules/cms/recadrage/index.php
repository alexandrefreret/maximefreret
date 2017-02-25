<?php

    /**
     * Created by PhpStorm.
     * User: Alexandre
     * Date: 15/01/2017
     * Time: 14:12
     */
    class _module extends __module
    {
        function switchAction()
        {
            //Page d'accueil du topic
            if(preg_match("#save.html#", $this->requete->url,$matches))
            {
                $this->save();
            }
            elseif(preg_match("#\/?#", $this->requete->url,$matches))
            {
                $this->index();
            }
        }


        function index()
        {
            global $template;

            $this->layout = "no_layout";

            $template->assign_vars($_GET);

            $this->render("index");
        }


        function save()
        {
            global $helper,$root;

            $helper->upload($_POST["type"], $_POST["base_img"], $this->get_encode_id($_POST["media_id"]));
            $helper->move_file();

            $sql = "SELECT media_id 
            FROM media 
            WHERE media_externid = ?
            AND media_type = ?";

            $media = $this->requete->querySecure($sql, [$_POST["media_id"], $_POST["type"]], true);
            
            if(!empty($media))
            {
                //J'update
                $update = array(
                    "media_filename" => $helper->get_filename()
                );

                $restrict = array(
                    "media_id" => $media["media_id"]
                );

                $this->requete->update("media", $update, $restrict);
            }
            else
            {
                $insert = array(
                    "media_type" => $_POST["type"],
                    "media_externid" => $_POST["media_id"],
                    "media_filename" => $helper->get_filename(),
                    "media_order" => 1,
                );
                $this->requete->insert("media", $insert);
            }
            
            switch ($_POST["type"])
            {
                case "news" :
                    require_once ($root . "objects/news.class.php");
                    $news = new News();
                    $actu = $news->get_news_id($this->get_encode_id($_POST["media_id"]));
                    $this->requete->redirect(WEBROOT . "admin/cms/actualites/editer/" . $actu["news_label_url"] . "," . $this->get_encode_id($_POST["media_id"]) . ".html");
                break;

                case "event" :
                    require_once ($root . "objects/event.class.php");
                    $event = new Event();
                    $actu = $event->get_event_id($this->get_encode_id($_POST["media_id"]));
                    $this->requete->redirect(WEBROOT . "admin/cms/evenement/editer/" . $actu["event_label_url"] . "," . $this->get_encode_id($_POST["media_id"]) . ".html");
                break;

                case "bandeau" :
                    $this->requete->redirect(WEBROOT . "admin/cms/bandeau/editer/" . $this->get_encode_id($_POST["media_id"]) . ".html");
                break;
            }


        }
    }