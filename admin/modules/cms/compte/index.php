<?php
    require_once ($root . "objects/compte.class.php");

    class _module extends __module
    {

        function switchAction()
        {
            //Page d'accueil du topic
            if(preg_match("#connexion#", $this->requete->url,$matches))
            {
                $this->connexion();
            }
            elseif(preg_match("#profil#", $this->requete->url,$matches))
            {
                $this->profil();
            }
            elseif(preg_match("#\/?#", $this->requete->url,$matches))
            {
                $this->index();
            }
        }



        function index()
        {
            global $template,$root;
            $this->title = "Compte";
            

            $this->render("index");
        }

        
        function profil()
        {
            global $template,$root;
            $this->title = "Compte";

            $compte = new Compte();

            $contact_id_encode = $this->get_encode_id($_SESSION["contact"]["contact_id"]);
            $contact = $compte->get_contact($contact_id_encode);

            if($contact["contact_img"] == "users.png")
            {
                $template->assign_block_vars("no_media", array());
            }
            else
            {
                $template->assign_block_vars("media", array());
            }

            $template->assign_vars($contact);

            $this->render("profil");
        }

        /**
         * Fonction qui gère la connexion de l'utilisateur avec un layout spécifique
         */
        function connexion()
        {
            global $template,$root;
            $this->layout = "connexion";


            //Si je suis connecté, je redirige sur la page d'accueil
//            if($this->requete->isLog())
//            {
//                $this->requete->redirect(WEBROOT . "admin/");
//            }

            //Si j'ai des POST, je veux me connecter
            if(!empty($_POST))
            {
                require_once($root . "objects/compte.class.php");
            
                $compte = new Compte($this->requete);
                
                $user_mail = $compte->get_contact_mail($_POST["email"]);
                
                if(!empty($user_mail))
                {
                    if($compte->verif_password($_POST["password"], $user_mail["contact_password"]))
                    {
                        //C'est ok je connecte,
                        $compte->connect($user_mail);

                        if(isset($_POST["remember"]))
                        {
                            //Je créer un cookie
                            $nextWeek = time() + (7 * 24 * 60 * 60);
                            setcookie("autologin",true,$nextWeek,'/');
                        }

                        $this->requete->redirect(WEBROOT . "admin/");
                    }
                    else
                    {
                        //Erreur
                        $template->assign_block_vars("error", array());
                    }
                }
                else
                {
                    //erreur
                    $template->assign_block_vars("error", array());
                }
            }

            $this->render("connexion");
        }




    }