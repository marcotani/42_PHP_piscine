<?php

    require_once 'HotBeverage.php';

    class Tea extends HotBeverage {
        private $description;
        private $commentaire;

        public function __construct() {
            parent::__construct("Tea", 0.99, 3);

            $this->description = "A soothing and aromatic tea, perfect for relaxation.";
            $this->commentaire = "Enjoy your tea with a slice of lemon or a touch of honey.";
        }

        public function getDescription() {
            return $this->description;
        }

        public function getCommentaire() {
            return $this->commentaire;
        }
    }

?>