<?php

class Game_dt extends CI_Model implements DatatableModel{


        public function appendToSelectStr() {
            return array();
        }

        public function fromTableStr() {
            return 'tbs_game';
        }

        public function joinArray(){
            return array();
        }

        public function whereClauseArray(){
            return array();
        }

        public function groupBy(){
            return array();
        }
   }

