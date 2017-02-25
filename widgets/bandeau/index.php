<?php
    require_once (ROOT . "objects/bandeau.class.php");

    $_widget_bandeau = new _widget_bandeau();
    class _widget_bandeau
    {

        function __construct()
        {
            global $template, $helper;

            //Je récupere les bandeaux que j'ai
            $bandeau = new Bandeau();

            $bandeaux = $bandeau->get_bandeau(0,4, "ASC");

            if(!empty($bandeaux))
            {
                foreach ($bandeaux as $band)
                {
                    $current_bandeau = array_merge($band, $band["media"][0]);
                    $current_bandeau["bandeau_id_encode"] = $helper->get_encode_id($current_bandeau["bandeau_id"]);

                    if($current_bandeau["bandeau_position"] == 1)
                    {
                        $current_bandeau["bandeau_class"] = "bandeau_left";
                    }
                    else
                    {
                        $current_bandeau["bandeau_class"] = "bandeau_right";
                    }
                    $template->assign_block_vars("bandeau", $current_bandeau);

                    if($current_bandeau["bandeau_label"] != "")
                    {
                        $template->assign_block_vars("bandeau.bandeau_label", array());
                    }


                    if($current_bandeau["bandeau_contenu"] != "")
                    {
                        $template->assign_block_vars("bandeau.bandeau_contenu", array());
                    }


                    if($current_bandeau["bandeau_button_link"] != "")
                    {
                        
                        $template->assign_block_vars("bandeau.bandeau_buttonlink", array());
                    }
                }
            }

        }

    }