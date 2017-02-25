<?php

    class Object
    {
        /**
         * @var Request
         */
        protected $requete;

        public function __construct()
        {
            global $requete;

            $this->requete = $requete;
        }

    }