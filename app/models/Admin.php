<?php

class Admin {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @function insertMenu
     * @param array
     * @return bool true on success else false
     */

    public function insertMenu($data){
        $this->db->query('INSERT INTO pages (label, link, target, body) VALUES (:label, :link, :target, :body)');
        $this->db->bind(':label', $data['label']);
        $this->db->bind(':link', $data['link']);
        $this->db->bind(':target', $data['target']);
        $this->db->bind(':body', $data['body']);

        if( $this->db->execute() ){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @function getAllMenu
     * @return array or menus itmes
     */
    public function getAllMenu(){
        $this->db->query('SELECT * FROM pages');
        $rows = $this->db->resultSet();
        return $rows;
    }
}


?>