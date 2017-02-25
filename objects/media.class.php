<?php


class Media extends Object
{

    public function get_media($extern_id,$type, $principale = false)
    {

        return $this->requete->querySecure("SELECT *
        FROM media 
        WHERE media_type = ?
        AND media_externid = ?
        ORDER BY media_order ASC", [$type, $extern_id], $principale);

    }
}