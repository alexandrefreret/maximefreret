<?php

    class Bandeau extends Object
    {
        function count_bandeau()
        {
            $sql="SELECT count(bandeau_id) as nb
            FROM bandeau
            ";
            $bandeau = $this->requete->query($sql,true);
            return $bandeau["nb"];
        }

        function get_bandeau($from,$limit, $order = "DESC")
        {
            $sql="SELECT *
            FROM bandeau
            ORDER BY bandeau_order $order
            LIMIT $from,$limit";
            $bandeau = $this->requete->query($sql);

            //Pour chaque bandeau je vais chercher les photos
            if(!empty($bandeau))
            {
                foreach ($bandeau as $key => $new)
                {
                    $sql = "SELECT *
                    FROM media
                    WHERE media_type = 'bandeau'
                    AND media_externid = '".$new["bandeau_id"]."'
                    ORDER BY media_order ASC";
                    $media = $this->requete->query($sql);
                    $bandeau[$key]["media"] = $media;
                }

                return $bandeau;
            }
            else
            {
                return array();
            }
        }


        function get_bandeau_id($id_encode)
        {
            global $helper;

            $sql = "SELECT *
            FROM bandeau 
            WHERE SUBSTR(SHA1(bandeau_id),1,13) = ? ";

            $bandeaux = $this->requete->querySecure($sql,[$id_encode],true);
            $bandeaux["bandeau_id_encode"] = $id_encode;
            $bandeaux["bandeau_label_url"] = $helper->clean_url($bandeaux["bandeau_label"]);
            
            if(!empty($bandeaux))
            {
                $sql = "SELECT *
                FROM media
                WHERE media_type = 'bandeau'
                AND media_externid = '".$bandeaux["bandeau_id"]."'
                ORDER BY media_order ASC";
                $media = $this->requete->query($sql);

                $bandeaux["media"] = $media;

                return $bandeaux;
            }

            return array();
        }


        function get_bandeau_categories()
        {
            $sql = "SELECT *
            FROM bandeau_categorie
            ORDER BY categorie_label ASC";

            return $this->requete->query($sql);
        }

        public function delete($bandeau_id_encode)
        {

            $sql = "DELETE FROM bandeau WHERE SUBSTR(SHA1(bandeau_id),1,13) = ?";

            $this->requete->toExecute($sql, [$bandeau_id_encode]);

        }
    }