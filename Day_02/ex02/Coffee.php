<?php

    require_once 'HotBeverage.php';

    class Coffee extends HotBeverage {
        private $description;
        private $commentaire;

        public function __construct() {
            parent::__construct("Coffee", 1.29, 1);

            $this->description = "A rich and aromatic coffee, perfect for a morning boost.";
            $this->commentaire = "Enjoy your coffee with a splash of milk or a sprinkle of sugar.";
        }

        public function getDescription() {
            return $this->description;
        }

        public function getCommentaire() {
            return $this->commentaire;
        }
    }

?>