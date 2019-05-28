<?php
class User{
    private  $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @param $data
     * @return bool
     *add new user in db
     */
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

    /**
     * @param $email
     * @return bool
     */
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();

        //check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return objact array
     */
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        return $row;
    }

    /**
     * @param $email
     * @param $password
     * @return bool false if password does not match else object array
     */
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

    /**
     * @param $id
     * @return mixed
     */

    public function getUserFollowing($id){
        $this->db->query('SELECT COUNT(follower_id) as following FROM followers WHERE follower_id = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserFollower($id){
        $this->db->query('SELECT COUNT(followee_id) as follower FROM followers WHERE followee_id = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    /**
     * @param $data
     * @return bool
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

    /**
     * @param $data
     * @return bool
     */
    public  function deleteFollower($data){
        $this->db->query('DELETE FROM followers WHERE follower_id=:follower_id AND followee_id= :followee_id');
        $this->db->bind(':follower_id', $data['follower_id']);
        $this->db->bind(':followee_id', $data['followee_id']);

        //execute
        if( $this->db->execute() ){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $followe_id
     * @param $follower_id
     * @return mixed
     */
    public  function checkUserAlreadyFollowed($followe_id, $follower_id){
        $this->db->query('SELECT * FROM followers WHERE follower_id = :follower_id AND followee_id  = :followe_id');
        $this->db->bind(':follower_id', $follower_id);
        $this->db->bind(':followe_id', $followe_id);
        $row = $this->db->single();

        return $row;
    }

    /**
     * @param $follower_id
     * @param $followe_id
     * @return mixed
     */
    public  function checkUserAlreadyFollowsMe( $follower_id, $followe_id){
        $this->db->query('SELECT * FROM followers WHERE follower_id = :follower_id AND followee_id  = :followe_id');
        $this->db->bind(':follower_id', $follower_id);
        $this->db->bind(':followe_id', $followe_id);
        $row = $this->db->single();
        return $row;
    }
}
