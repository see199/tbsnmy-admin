<?php

class Dizang_dt extends CI_Model implements DatatableModel{


        public function appendToSelectStr() {
                return array(
                    'viewmodal' => 'dizang_id',
                    'name' => 'CONCAT(applicant_name," ",COALESCE(applicant_name_2,""))',
                    'contact' => 'CONCAT(applicant_contact," ",COALESCE(applicant_contact_2,"")," ",COALESCE(applicant_contact_3,""))',
                    'deceased' => 'CONCAT(deceased_name," ",COALESCE(deceased_name_2,""))',
                );

        }

        public function fromTableStr() {
            return 'tbs_dizang';
        }

        public function joinArray(){
            return array(
                //'company c' => 'c.cid = p.company'
            );
        }

        public function whereClauseArray(){
            return array(
                //'p.status !=' => 'Deleted',
                'date >=' => $this->input->post('date_from'),
                'date <=' => $this->input->post('date_to'),
            );
        }

        public function groupBy(){
            return array();
        }
   }

