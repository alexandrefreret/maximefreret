<?php


    class Page extends Object
    {

        function get_page_site($url)
        {
            $sql = "SELECT *
            FROM page
            WHERE page_url = ?
            AND page_active = 1";
            return $this->requete->querySecure($sql, [$url],true);
        }


        function get_pages()
        {
            $sql = "SELECT *
            FROM page
            ORDER BY page_label ASC";

            return $this->requete->querySecure($sql, []);

        }
    }