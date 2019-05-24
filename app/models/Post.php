<?php

    class Post{
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function getPosts(){
//            $total = 5;
//            $next_page = 5;

            $this->db->query('SELECT *,
            posts.id as postId,
            users.id as userId,             
            LEFT(posts.body, 100) as body,
            posts.created_At as postCreated
            FROM posts
             INNER JOIN users
              ON posts.user_id = users.id 
             ORDER BY posts.created_At DESC');


            // LIMIT :limit, :offset

//            $this->db->bind(':limit',$total, PDO::PARAM_INT);
         //   $this->db->bind(':offset',$next_page, PDO::PARAM_INT);
//            $this->db->bind(':numRows',$next_page, PDO::PARAM_INT);
            $result = $this->db->resultSet();

            return $result;
        }

        /*
         * Get count of total no of posts
         */
        public function getCountOfPost(){
            $this->db->query('SELECT * FROM posts');
            $this->db->execute();
            $rows = $this->db->rowCount();
            return $rows;
        }

        public function addPost($data){
            $this->db->query('INSERT INTO posts (title, user_id, body) VALUES (:title, :user_id, :body)');
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':body', $data['body']);

            //execute
            if( $this->db->execute() ){
                return true;
            } else {
                return false;
            }
        }

        public function updatePost($data){
            $this->db->query('UPDATE posts SET title= :title,  body=:body WHERE id=:id');

            $this->db->bind(':id', $data['id']);
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':body', $data['body']);

            //execute
            if( $this->db->execute() ){
                return true;
            } else {
                return false;
            }
        }

        public function getPostById($id){
            $this->db->query('SELECT * FROM posts WHERE id =:id');
            $this->db->bind(':id', $id);

            $row = $this->db->single();
            return $row;
        }

        public function deletePost($id){
            $this->db->query('DELETE FROM posts WHERE id=:id');

            $this->db->bind(':id', $id);
            //execute
            if( $this->db->execute() ){
                return true;
            } else {
                return false;
            }
        }

    }
