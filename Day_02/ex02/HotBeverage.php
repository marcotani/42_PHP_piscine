<?php

    class HotBeverage {
        protected $nom;
        protected $prix;
        protected $resistance;

        public function __construct($nom, $prix, $resistance) {
            $this->nom = $nom;
            $this->prix = $prix;
            $this->resistance = $resistance;
        }

        public function getNom() {
            return $this->nom;
        }

        public function getPrix() {
            return $this->prix;
        }

        public function getResistance() {
            return $this->resistance;
        }
    }

?>