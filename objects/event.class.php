<?php

    class Event extends Object
    {
        function count_event()
        {
            $sql="SELECT count(event_id) as nb
            FROM event
            LEFT JOIN contact ON contact_id = event_contact
            ";
            $event = $this->requete->query($sql,true);
            return $event["nb"];
        }

        function get_event($from,$limit)
        {
            $sql="SELECT *,
            DATE_FORMAT(event_datedebut, '%d/%m/%Y') as event_datedebut_fr,
            DATE_FORMAT(event_datefin, '%d/%m/%Y') as event_datefin_fr,
            DATE_FORMAT(event_datedebut, '%H:%i') as event_datedebut_heure,
            DATE_FORMAT(event_datefin, '%H:%i') as event_datefin_heure
            FROM event
            LEFT JOIN contact ON contact_id = event_contact
            ORDER BY event_updated DESC
            LIMIT $from,$limit";
            $event = $this->requete->query($sql);

            //Pour chaque event je vais chercher les photos
            if(!empty($event))
            {
                foreach ($event as $key => $new)
                {
                    $sql = "SELECT *
                    FROM media
                    WHERE media_type = 'event'
                    AND media_externid = '".$new["event_id"]."'
                    ORDER BY media_order ASC";
                    $media = $this->requete->query($sql);
                    $event[$key]["media"] = $media;
                }

                return $event;
            }
            else
            {
                return array();
            }
        }


        function get_event_id($id_encode)
        {
            global $helper;

            $sql = "SELECT *,
            DATE_FORMAT(event_datedebut, '%d/%m/%Y') as event_datedebut_fr,
            DATE_FORMAT(event_datefin, '%d/%m/%Y') as event_datefin_fr,
            DATE_FORMAT(event_datedebut, '%H:%i') as event_datedebut_heure,
            DATE_FORMAT(event_datefin, '%H:%i') as event_datefin_heure
            FROM event 
            LEFT JOIN contact ON contact_id = event_contact
            WHERE SUBSTR(SHA1(event_id),1,13) = ? ";

            $actualites = $this->requete->querySecure($sql,[$id_encode],true);
            $actualites["event_id_encode"] = $id_encode;
            $actualites["event_label_url"] = $helper->clean_url($actualites["event_label"]);

            if(!empty($actualites))
            {
                $sql = "SELECT *
                FROM media
                WHERE media_type = 'event'
                AND media_externid = '".$actualites["event_id"]."'
                ORDER BY media_order ASC";
                $media = $this->requete->query($sql);

                $actualites["media"] = $media;

                return $actualites;
            }

            return array();
        }


        function get_event_categories()
        {
            $sql = "SELECT *
            FROM event_categorie
            ORDER BY categorie_label ASC";

            return $this->requete->query($sql);
        }

        public function delete($event_id_encode)
        {

            $sql = "DELETE FROM event WHERE SUBSTR(SHA1(event_id),1,13) = ?";

            $this->requete->toExecute($sql, [$event_id_encode]);

        }
    }