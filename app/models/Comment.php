<?php

    class Comment {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        /*
        * Add comment to post table
        * with user id, post id , comment text
        */

        public function addComment($data){
            $this->db->query('INSERT INTO comments (post_id, user_id, comment) VALUES (:post_id, :user_id, :comment)');
            $this->db->bind(':post_id', $data['post_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':comment', $data['comment']);

            //execute
            if( $this->db->execute() ){
                return true;
            } else {
                return false;
            }
        }

        /*
         * Get comment by post id
         */
        public function getCommentsDetails($post_id){
            $this->db->query('SELECT *,
            users.name as user_name,
            comments.created_At as commented_on,
            comments.id as comment_id            
            FROM comments
             INNER JOIN users
              ON comments.user_id = users.id AND comments.post_id = :post_id
             ORDER BY comments.created_At DESC ');
            $this->db->bind(':post_id', $post_id);

            $row = $this->db->resultSet();
            return $row;
        }

        /*
         * Get comment by id
         */

        public function getCommentById($id){
            $this->db->query('SELECT * FROM comments WHERE id =:id');
            $this->db->bind(':id', $id);

            $row = $this->db->single();
            return $row;
        }

        /*
         * Delete comment
         */

        public function deleteComment($id){
            $this->db->query('DELETE FROM comments WHERE id=:id');

            $this->db->bind(':id', $id);
            //execute
            if( $this->db->execute() ){
                return true;
            } else {
                return false;
            }
        }

    }


?>