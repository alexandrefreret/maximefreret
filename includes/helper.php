<?php

    /**
     * Created by PhpStorm.
     * User: Alexandre
     * Date: 23/12/2016
     * Time: 09:15
     */
    class Helper
    {

        private $type_img;
        private $img_data;
        private $dir;
        private $filename;
        private $id_encode;

        function date2en($dateFR) {
            $a = explode('/', str_replace('-', '/', trim($dateFR)));

            return $a[2] . '-' . $a[1] . '-' . $a[0];
        }
        function date2fr($dateEN) {
            $a = explode('-', $dateEN);

            return $a[2] . '/' . $a[1] . '/' . $a[0];
        }

        function normalizeString ($str = '')
        {
            $str = strip_tags($str);
            $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
            $str = preg_replace('/[\"\*\/\:\<\>\?\'\|\.]+/', ' ', $str);
            $str = strtolower($str);
            $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
            $str = htmlentities($str, ENT_QUOTES, "utf-8");
            $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
            $str = str_replace(' ', '-', $str);
            $str = rawurlencode($str);
            $str = str_replace('%', '-', $str);
            return $str;
        }

        function clean_url($str) {
            $str = html_entity_decode($str);
            $ret = $this->stripAccents($str);
            $ret = strtolower($ret);
            $ret = str_replace(array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "/", "?", "(", ")", "[", "]", "{", "}", "*", "	"), "", $ret);
            $ret = str_replace(array(" ", "_", ",", "+", "'", '"', ':', '&', '.', '!', "
            "), "-", $ret);
            $ret_array = explode("-", $ret);
            foreach ($ret_array as $k => $v) {
                if (strlen($v) < 3)
                    unset($ret_array[$k]);
            }
            $ret = implode("-", $ret_array);
            $ret = utf8_decode($ret);
            $ret = urldecode($ret);
            $ret = urlencode($ret);
            return $ret;
        }



        function clean_filename($str) {
            $str = html_entity_decode($str);
            $ret = $this->stripAccents($str);
            $ret = strtolower($ret);
            $ret = str_replace(array("/", "?", "(", ")", "[", "]", "{", "}", "*", "   "), "", $ret);
            $ret = str_replace(array(" ", "_", ",", "+", "'", '"', ':', '&', '.', '!', "
            "), "-", $ret);
            $ret_array = explode("-", $ret);
            $ret = implode("-", $ret_array);
            $ret = utf8_decode($ret);
            $ret = urldecode($ret);
            $ret = urlencode($ret);
            return $ret;
        }


        function stripAccents($string) {
            //return strtr($string, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $charset='utf-8';
            $str = htmlentities($string, ENT_NOQUOTES, $charset);

            $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
            $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
            $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

            return $str;
        }



        /**
         * Truncates text.
         *
         * Cuts a string to the length of $length and replaces the last characters
         * with the ending if the text is longer than length.
         *
         * @param string  $text String to truncate.
         * @param integer $length Length of returned string, including ellipsis.
         * @param mixed $ending If string, will be used as Ending and appended to the trimmed string. Can also be an associative array that can contain the last three params of this method.
         * @param boolean $exact If false, $text will not be cut mid-word
         * @param boolean $considerHtml If true, HTML tags would be handled correctly
         * @return string Trimmed string.
         */
        function truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
            if (is_array($ending)) {
                extract($ending);
            }
            if ($considerHtml) {
                if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                    return $text;
                }
                $totalLength = mb_strlen($ending);
                $openTags = array();
                $truncate = '';
                preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
                foreach ($tags as $tag) {
                    if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                        if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                            array_unshift($openTags, $tag[2]);
                        } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                            $pos = array_search($closeTag[1], $openTags);
                            if ($pos !== false) {
                                array_splice($openTags, $pos, 1);
                            }
                        }
                    }
                    $truncate .= $tag[1];
                    $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                    if ($contentLength + $totalLength > $length) {
                        $left = $length - $totalLength;
                        $entitiesLength = 0;
                        if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                            foreach ($entities[0] as $entity) {
                                if ($entity[1] + 1 - $entitiesLength <= $left) {
                                    $left--;
                                    $entitiesLength += mb_strlen($entity[0]);
                                } else {
                                    break;
                                }
                            }
                        }
                        $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                        break;
                    } else {
                        $truncate .= $tag[3];
                        $totalLength += $contentLength;
                    }
                    if ($totalLength >= $length) {
                        break;
                    }
                }
            } else {
                if (mb_strlen($text) <= $length) {
                    return $text;
                } else {
                    $truncate = mb_substr($text, 0, $length - strlen($ending));
                }
            }
            if (!$exact) {
                $spacepos = mb_strrpos($truncate, ' ');
                if (isset($spacepos)) {
                    if ($considerHtml) {
                        $bits = mb_substr($truncate, $spacepos);
                        preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                        if (!empty($droppedTags)) {
                            foreach ($droppedTags as $closingTag) {
                                if (!in_array($closingTag[1], $openTags)) {
                                    array_unshift($openTags, $closingTag[1]);
                                }
                            }
                        }
                    }
                    $truncate = mb_substr($truncate, 0, $spacepos);
                }
            }
            $truncate .= $ending;
            if ($considerHtml) {
                foreach ($openTags as $tag) {
                    $truncate .= '</'.$tag.'>';
                }
            }
            return $truncate;
        }


        public function upload($type_img,$infos_img,$id_encode)
        {
            global $requete;
            $this->type_img = $type_img;

            $this->img_data = $infos_img;
            $this->id_encode = $id_encode;

            $pos  = strpos($this->img_data, ';');
            $type = explode(':', substr($this->img_data, 0, $pos))[1];

            $extension = explode("/", $type)[1];

            $this->filename = substr( base_convert( time(), 10, 36 ) . md5( microtime() ), 0, 16 ).".".$extension;
            $this->dir = $requete->site_path."front/assets/img/".$this->type_img."/illustration/". $this->id_encode."/";
            $this->webdir = WEBROOT."assets/img/".$this->type_img."/illustration/". $this->id_encode."/";
            $this->check_dir();
        }


        public function check_dir()
        {

            //J'explode le dir, je regarde si tous les dossiers existe, sinon je le créer
            $dirs = explode("/", $this->dir);

            $path = "";
            foreach ($dirs as $dir)
            {
                if(!file_exists($path.$dir))
                {
                    //Je le créer
                    mkdir($path.$dir);
                    chmod($path.$dir, 0755);
                }
                $path .= $dir."/";

            }
        }


        public function move_file()
        {
            return file_put_contents($this->dir.$this->filename, base64_decode(substr($this->img_data, strpos($this->img_data, ",")+1)));
        }


        function get_filename()
        {
            return $this->filename;
        }


        public function get_encode_id($id)
        {
            return substr(sha1($id),0,13);
        }

    }