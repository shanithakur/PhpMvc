<?php
    class Contact {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        /**
         *
         */
        public function getPageContent($page_name){
            $this->db->query('SELECT * FROM pages WHERE link=:link');
            $this->db->bind(':link', $page_name);
            $row = $this->db->single();
            return $row;
        }

    }
?>