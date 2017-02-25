<?php

    /**
     * Created by PhpStorm.
     * User: Alexandre
     * Date: 15/12/2016
     * Time: 22:03
     */
    class __module
    {

        protected $requete;
        protected $layout = "layout";
        protected $title = "";

        /**
         * __module constructor.
         *
         * @param $requete Request
         */
        function __construct()
        {
            global $requete;

            $this->requete = $requete;
            $this->has_access();
            $this->switchAction();
        }

        function switchAction()
        {
            //Page d'accueil du topic
            if(preg_match("#\/#", $this->requete->url,$matches))
            {
                $this->index();
            }
            
        }

        function index(){}

        public function render($filename)
        {
            global $template,$root_path,$widget,$root;

            if($this->requete->topic_type == "")
            {
                //C'est que je suis dans le statique
                $this->requete->topic_type = "statique";
            }
            $template->assign_vars(array("WEBROOT"=>WEBROOT,"TITLE" => $this->title));

            if(isset($_SESSION["contact"]))
            {
                $template->assign_vars($_SESSION["contact"]);
                if($_SESSION["contact"]["contact_img"] != "users.png")
                {
                    $template->assign_block_vars("contact_photo", array());
                }
                else
                {
                    $template->assign_block_vars("no_contact_photo", array());
                }
            }

            $path_module = "modules/" . $this->requete->topic_type . "/";

            $template->set_filenames(array("layout"=> "modules/statique/template/" . $this->layout . ".tpl"));
            $template->set_filenames(array("body"=> $path_module . "template/" . $filename.'.tpl'));


            $template->assign_var_from_handle('CONTENT', 'body');
            $this->readAlert();

            //J'inclus les widgets qui sont en before
            if(!empty($widget->widget_after))
            {
                foreach ($widget->widget_after as $widget_after)
                {
                    require_once ($root . "widgets/" . $widget_after["widget_dir"] . "/index.php");
                }
            }



            $template->pparse('layout');
        }

        private function readAlert()
        {

        }


        private function has_access()
        {
            if(!$this->requete->is_active_module($this->requete->topic_type))
            {
                $this->requete->erreur_404($this);
            }
        }

        public function get_encode_id($id)
        {
            return substr(sha1($id),0,13);
        }
        
        //
//        function load_render($filename)
//        {
//            global $template;
//            $template->assign_vars(array("ADMIN" => ADMIN, "WEBROOT" => WEBROOT, "TITLE_SITE" => $_ENV['title_site']));
//
//            /*
//             * Utilisation
//                $this->layout = "template_mail";
//                $params['certificat'] = $upload;
//                ob_start();
//                $this->set($params);
//                $this->load_render('infos_inscription');
//                return ob_get_clean();
//             *
//             *
//             * */
//
//
//            $ctrl = str_replace('_controller', '', get_class($this));
//            ob_start();
//
//            $template->set_filenames(array("contenu" => $ctrl . "/" . $filename . '.tpl'));
//
//            $content_for_layout = ob_get_clean();
//            $template->pparse('contenu');
//        }

    }