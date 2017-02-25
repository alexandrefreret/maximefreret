<?php

    /**
     * Created by PhpStorm.
     * User: Alexandre
     * Date: 24/01/2017
     * Time: 18:00
     */
    class Param extends Object
    {
        function get_params()
        {
            $sql = "SELECT *
            FROM c_param";
            
            return $this->requete->querySecure($sql, []);
        }
    }