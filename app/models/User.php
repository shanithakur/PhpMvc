<?php
class User{
    private  $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public  function register($data){
        $this->db->query('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        //execute
        if( $this->db->execute() ){
            return true;
        } else {
            return false;
        }
    }


    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        //check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        return $row;
    }


    public function login($email, $password){
        $this->db->query('SELECT * FROM users WHERE email =:email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)){
            return $row;
        } else {
            return false;
        }
    }

    /*
     * Get count of no of users follow by user
     */

    public function getUserFollowing($id){
        $this->db->query('SELECT COUNT(follower_id) as following FROM followers WHERE follower_id = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    /*
     * Get count of no of users who followed user
     */

    public function getUserFollower($id){
        $this->db->query('SELECT COUNT(followee_id) as follower FROM followers WHERE followee_id = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    /*
     * Add follower record to db
     */
    public  function addFollower($data){
        $this->db->query('INSERT INTO followers (follower_id, followee_id) VALUES (:follower_id, :followee_id)');
        $this->db->bind(':follower_id', $data['follower_id']);
        $this->db->bind(':followee_id', $data['followee_id']);

        //execute
        if( $this->db->execute() ){
            return true;
        } else {
            return false;
        }
    }

    /*
     * Check user already followed or not
     */
    public  function checkUserAlreadyFollowed($followe_id, $follower_id){
        $this->db->query('SELECT * FROM followers WHERE follower_id = :follower_id AND followee_id  = :followe_id');
        $this->db->bind(':follower_id', $follower_id);
        $this->db->bind(':followe_id', $followe_id);
        $row = $this->db->single();

        return $row;
    }

    /*
 * Check user already follows me or not
 */
    public  function checkUserAlreadyFollowsMe( $follower_id, $followe_id){
        //var_dump($follower_id."".$followe_id); exit();
        $this->db->query('SELECT * FROM followers WHERE follower_id = :follower_id AND followee_id  = :followe_id');
        $this->db->bind(':follower_id', $follower_id);
        $this->db->bind(':followe_id', $followe_id);
        $row = $this->db->single();
        return $row;
    }
}
