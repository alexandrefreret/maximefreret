<?php
    session_start();

    define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
    define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));


    $path_media = WEBROOT . $_GET["path"];
    $path_media_require = $_GET["path"];


        //Je remplace WEBROOT/admin/ par rien, pour avoir mon url pour chercher dans les topics
    $_SERVER['REQUEST_URI'] = preg_replace("#" . WEBROOT . "(admin\/?|front\/?)?#", "", $_SERVER['REQUEST_URI']);


    $path = $_GET["path"];
    $root = "./";
    $root_path = $root . $_GET["path"] ;
    require_once($root . "includes/request.php");
    require_once($root . "includes/widget.php");
    require_once($root . "includes/helper.php");
    require_once($root . "includes/object.class.php");
    require_once($root . "includes/module.php");
    require_once($root . "includes/template.php");

    $template = new Template($root_path);
    $helper = new Helper();
    $requete = new Request();

    $widget = new Widget();

    $template->assign_var("path", $root_path);
    $template->assign_var("path_media", $path_media);


    //Je vérifie que l'utilisateur est bien loggué
    if($path == "admin/")
    {
        if($requete->isLog())
        {

        }
        else
        {

            if(trim($requete->url,"/") != "connexion")
            {
                $requete->redirect(WEBROOT."admin/cms/compte/connexion");
            }
        }
    }


    //J'inclus les widgets qui sont en before
    if(!empty($widget->widget_before))
    {
        foreach ($widget->widget_before as $widget_before)
        {
            require_once ($root . "widgets/" . $widget_before["widget_dir"] . "/index.php");
        }
    }

  
    //Je vérifie que le module existe bien
    if(file_exists($root_path . 'modules/' . $requete->topic_type . '/index.php'))
    {
        //J'inclus le module dans lequel je suis (avec le topic_type)
        require_once($root_path . 'modules/' . $requete->topic_type . '/index.php');
        $module = new _module();
    }
    else
    {
        //J'inclus le module statique pour avoir la page 404
        require_once($root_path . 'modules/statique/index.php');
        
        $module = new _module();
        $requete->erreur_404($module);
    }

    