<?php


    class Request
    {
        public $topic_url;
        private $db_host;
        private $db_name;
        private $db_user;
        private $db_password;
        public $url;
        private $topictype_site = 0;
        public $topic;

        private $pdo;


        function __construct()
        {
            global $root_path,$template, $root,$path, $path_media_require;

            $this->_db();
            $template->assign_vars($this);

            //Je regarde si je suis sur le site, ou coté admin pour aller chercher dans les topics
            if($_GET["path"] == "front/")
            {
                $this->topictype_site = 1;
            }


             if (preg_match('/^(\/?.*)/', $_SERVER['REQUEST_URI'], $regs))
             {
                 $this->url = $regs[1];
             }
            

            //Je vire les params $_GET d'apres l'url pour etre propre
            $this->url = preg_replace("#(\?(.*))#", "", $this->url);
            //Ici je gere les ressources ("css,js, img ...)
            if(preg_match('/^(.*)(\.css|\.js|\.jpg|\.jpeg|\.svg|\.png|\.woff|\.ttf|\.woff2)(.*)?$/', $this->url, $regs))
            {
                if(file_exists($path_media_require . $this->url))
                {
                    $this->requireFile($path_media_require . $this->url);
                }
                else
                {
                    header("HTTP/1.1 404 Not found");
                    header("Status: 404 Not found");
                }
                die();
            }
            else
            {
                //Pour la page d'accueil
                if($this->url == "" && $_GET["path"] != "front/")
                {
                    $this->url = "cms/";
                }
                elseif($this->url == "")
                {
                    $this->url = "/";
                }

                $topic = $this->search_topic($this->url);

                //Si j'ai un topic correspondant
                if(!empty($topic))
                {
                    $this->topic_type = $topic["topictype_label"];
                }
                else
                {
                    $this->topic_type = "statique";
                }
            }
        }


        function search_topic($topic_url) {
            global $db;


            $sql = "SELECT * 
            FROM topic
            INNER JOIN topic_type ON topictype_id = topic_type
            WHERE topic_url = ?
            AND topictype_site = ?";

            $result = $this->querySecure($sql,[$topic_url, $this->topictype_site],true);
            if(!empty($result))
            {
                $this->url = str_replace($result["topic_url"]."/","",$this->url);
                $this->topic_url = $result["topic_url"];
                $this->topic = $result;
                return $result;
            }
            else
            {
                if ($topic_url == "")
                {
                    return array();
                } else {
                    $topic_url = substr($topic_url, 0, strrpos($topic_url, "/"));
                    return $this->search_topic($topic_url);
                }
            }
        }

        function is_active_module($topic_type)
        {
            $sql="SELECT modules_label
            FROM modules
            INNER JOIN topic ON topic_modules = modules_id
            INNER JOIN topic_type ON topictype_id = topic_type
            AND topictype_label = ? 
            AND modules_active = 1";

            $module = $this->querySecure($sql, [$topic_type],true);
            if(!empty($module))
            {
                return true;
            }
            return false;
        }

        /**
         * @param $module, Instance de __module pour avoir acces a la méthode render
         */
        function erreur_404(__module $module)
        {
            global $template;
            
            header("HTTP/1.1 404 Not found");
            header("Status: 404 Not found");

            $this->topic_type = "statique";

            $module->render("404");
            exit;
        }


        function redirect($url = "/")
        {
            header("Location: ".$url);
            die();
        }


        function is_ajax()
        {
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                return true;
            }

            return false;
        }


        function isLog()
        {
            if(isset($_SESSION["contact"]) && !empty($_SESSION["contact"]))
            {
                return true;
            }

            return false;
        }



        










        /**
         * Connexion a la base de données
         */
        public function _db()
        {
            global $root_path;

            //Je récupere le contenu du fichier .env
            $var_env = file($root_path . ".env");
            if(!empty($var_env))
            {
                foreach ($var_env as $item)
                {
                    $re = '/([A-Za-z_]*)="([a-zA-Z0-9_\-\:\/\. ]*)/';

                    preg_match_all($re, $item, $matches,PREG_SET_ORDER);

                    // Print the entire match result
                    $this->{$matches[0][1]} = $matches[0][2];
                }
            }
        }


        private function getPDO()
        {
            if($this->pdo === null && $this->db_host != null)
            {
                $db = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name ,$this->db_user ,$this->db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
                $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
                $this->pdo = $db;
            }

            return $this->pdo;

        }


        function debug($statement, array $params = [])
        {
            $statement = preg_replace_callback(
                '/[?]/',
                function ($k) use ($params) {
                    static $i = 0;
                    return sprintf("'%s'", $params[$i++]);
                },
                $statement
            );

            //        echo '<pre>Query Debug:<br>', $statement, '</pre>';
            return $statement;
        }



        /**
         * Retourne les résultats d'une requete
         * @param String $requete La requete
         * @param bool $one Si il est à true, renvoie le 1er resultat
         *
         * @return array|mixed
         */
        public function query($requete, $one = false,$debug = false)
        {
            $req = $this->getPDO()->query($requete);
            if($debug)
            {
                echo '<pre>'; var_dump($this->debug($requete)); echo '</pre>';
            }
            if($one)
                return $req->fetch();
            else
                return $req->fetchAll();
        }


        /**
         * Renvoie le dernier enregistrement en base de données
         * @param null $name
         *
         * @return string
         */
        public function lastInsertId($name = NULL) {
            return $this->getPDO()->lastInsertId($name);
        }


        /**
         * Permet de sécuriser une requete tel que les insert et de les executer
         * @param $requete
         *
         * @return PDOStatement
         */
        public function toExecute($requete,$params,$debug = false)
        {
            if($debug)
            {
                echo '<pre>'; var_dump($this->debug($requete,$params)); echo '</pre>';
            }
            return $this->getPDO()->prepare($requete)->execute($params);
        }


        public function querySecure($requete,$params,$one = false,$debug = false)
        {
            $sql = $this->getPDO()->prepare($requete);
            $sql->execute($params);
            if($debug)
            {
                echo '<pre>'; var_dump($this->debug($requete,$params)); echo '</pre>';
            }
            if($one)
                return $sql->fetch();

            else
                return $sql->fetchAll();
        }


        /**
         * Permet de génerer un requete d'insertion facilement
         * @param $table
         * @param $params array, Tableau des parametres avec les clés
         *
         * @return bool|string
         */
        public function insert($table,$params)
        {
            if(!empty($params) && $table != "")
            {

                $sql = "INSERT INTO " . $table . " (";
                $values = "";
                $array_exec = array();
                foreach ($params as $key=>$param)
                {
                    $sql .= $key.", ";
                    $values .= "?,";
                    $array_exec[] = $param;
                }

                $sql = substr($sql,0,-2);
                $values = substr($values,0,-1);
                $sql .= ") VALUES (".$values.")";

                $this->getPDO()->prepare($sql)->execute($array_exec);
                return $this->lastInsertId();
            }
            return false;
        }


        /**
         * Permet de générer une requete de mise a jour rapidement
         * @param $table String, Nom de la table a changer
         * @param $params array, Tableau des parametres avec les clés (SET)
         * @param $restricts array, Tableau des restriction avec les clés (WHERE)
         *
         * @return bool
         */
        function update($table,$params,$restricts)
        {
            if(!empty($params) && $table != "")
            {

                $sql = "UPDATE ".$table." SET";
                $array_exec = array();
                foreach ($params as $key=>$param)
                {
                    $sql .= " ".$key." = ?,";
                    $array_exec[] = $param;

                }

                $sql = substr($sql,0,-1);

                $sql .= " WHERE";
                foreach ($restricts as $key=>$restric)
                {
                    $sql .= " ".$key." = ? AND";
                    $array_exec[] = $restric;
                }
                $sql = substr($sql,0,-4);

                return $this->getPDO()->prepare($sql)->execute($array_exec);
            }
            return false;
        }

        private function requireFile($name)
        {
            ob_start("ob_gzhandler");
            session_write_close();

            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($name)) . ' GMT', true, 200);
            $expires = 60 * 60 * 24 * 20; // Expire dans une semaine.
            header("Pragma: public");
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
            header("Cache-Control: must-revalidate");

            header("Content-Type: " . $this->mime_content_type($name));
            header("Content-Length: " . filesize($name));
            if(isset($_GET['cross_domain']))
            {
                header('Access-Control-Allow-Origin: *');
            }

            echo file_get_contents($name);
            die();

        }


        function mime_content_type($filename) {

            $mime_types = array(
                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',
                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',
                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',
                // audio/video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',
                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',
                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',
                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            );

            $ext = @strtolower(array_pop(explode('.', $filename)));
            if (array_key_exists($ext, $mime_types)) {
                return $mime_types[$ext];
            } elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mimetype = finfo_file($finfo, $filename);
                finfo_close($finfo);
                return $mimetype;
            } else {
                return 'application/octet-stream';
            }
        }
    }