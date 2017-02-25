<?php

    class News extends Object
    {
        function count_news()
        {
            $sql="SELECT count(news_id) as nb
            FROM news
            LEFT JOIN contact ON contact_id = news_contact
            ";
            $news = $this->requete->query($sql,true);
            return $news["nb"];
        }

        function get_news($from,$limit)
        {
            $sql="SELECT *,
            DATE_FORMAT(news_datedebut, '%d/%m/%Y') as news_datedebut_fr,
            DATE_FORMAT(news_datefin, '%d/%m/%Y') as news_datefin_fr,
            DATE_FORMAT(news_datedebut, '%H:%i') as news_datedebut_heure,
            DATE_FORMAT(news_datefin, '%H:%i') as news_datefin_heure
            FROM news
            LEFT JOIN contact ON contact_id = news_contact
            ORDER BY news_updated DESC
            LIMIT $from,$limit";
            $news = $this->requete->query($sql);

            //Pour chaque news je vais chercher les photos
            if(!empty($news))
            {
                foreach ($news as $key => $new)
                {
                    $sql = "SELECT *
                    FROM media
                    WHERE media_type = 'news'
                    AND media_externid = '".$new["news_id"]."'
                    ORDER BY media_order ASC";
                    $media = $this->requete->query($sql);
                    $news[$key]["media"] = $media;
                }

                return $news;
            }
            else
            {
                return array();
            }
        }


        function get_news_id($id_encode)
        {
            global $helper;

            $sql = "SELECT *,
            DATE_FORMAT(news_datedebut, '%d/%m/%Y') as news_datedebut_fr,
            DATE_FORMAT(news_datefin, '%d/%m/%Y') as news_datefin_fr,
            DATE_FORMAT(news_datedebut, '%H:%i') as news_datedebut_heure,
            DATE_FORMAT(news_datefin, '%H:%i') as news_datefin_heure
            FROM news 
            LEFT JOIN contact ON contact_id = news_contact
            WHERE SUBSTR(SHA1(news_id),1,13) = ? ";

            $actualites = $this->requete->querySecure($sql,[$id_encode],true);
            $actualites["news_id_encode"] = $id_encode;
            $actualites["news_label_url"] = $helper->clean_url($actualites["news_label"]);

            if(!empty($actualites))
            {
                $sql = "SELECT *
                FROM media
                WHERE media_type = 'news'
                AND media_externid = '".$actualites["news_id"]."'
                ORDER BY media_order ASC";
                $media = $this->requete->query($sql);

                $actualites["media"] = $media;

                return $actualites;
            }

            return array();
        }


        function get_news_categories()
        {
            $sql = "SELECT *
            FROM news_categorie
            ORDER BY categorie_label ASC";

            return $this->requete->query($sql);
        }

        public function delete($news_id_encode)
        {

            $sql = "DELETE FROM news WHERE SUBSTR(SHA1(news_id),1,13) = ?";

            $this->requete->toExecute($sql, [$news_id_encode]);

        }
    }