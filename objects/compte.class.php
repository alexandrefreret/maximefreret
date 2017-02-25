<?php

    class Compte extends Object
    {
        function get_contact_mail($mail)
        {
            $sql="SELECT *
            FROM contact
            WHERE contact_mail = ?";

            return $this->requete->querySecure($sql, [$mail],true);
        }

        function get_contact($id_encode)
        {
            $sql = "SELECT *, SUBSTR(SHA1(contact_id),1,13) as contact_id_encode,
            GROUP_CONCAT(' ',categorie_label) as categorie_label
            FROM contact
            LEFT JOIN contact_categorie_lien ON lien_contact = contact_id
            LEFT JOIN contact_categorie ON lien_categorie = categorie_id
            WHERE SUBSTR(SHA1(contact_id),1,13) = ?";

            return $this->requete->querySecure($sql, [$id_encode], true);
        }


        function verif_password($password,$contact_password)
        {
            if(password_verify($password,$contact_password))
            {
                return true;
            }

            return false;
        }

        public function connect($contact)
        {
            $_SESSION["contact"] = $contact;
        }


        function create_user($contact)
        {
            return $this->requete->insert("contact", $contact);
        }



    }


    //password_hash($_POST['password'], PASSWORD_BCRYPT, array("cost"=>12))