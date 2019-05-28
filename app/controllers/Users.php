<?php

class Users extends Controller {

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function index(){

    }

    /**
     * validate form data
     * register new user to db
     * throw error if user already exits
     */
    public function register(){
        //check for post
        if($_SERVER['REQUEST_METHOD']== 'POST'){
            //Process form

            //sanitize form data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' =>trim($_POST['name']),
                'email' =>trim($_POST['email']),
                'password' =>trim($_POST['password']),
                'confirm_password' =>trim($_POST['confirm_password']),
                'name_err' =>'',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' =>''

            ];

            //validate email
            if(empty($data['email'])){
                $data['email_err'] = "Please enter email";
            } else{
                //check email
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email already taken';
                }
            }

            //validate name
            if(empty($data['name'])){
                $data['name_err'] = "Please enter name";
            }

            //validate password
            if(empty($data['password'])){
                $data['password_err'] = "Please enter password";
            } elseif (strlen($data['password']) <6 ){
                $data['password_err'] = "Password must be at least 6 character";
            }

            //validate confirm password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = "Please confirm password";
            } else {
                if ($data['password'] !== $data['confirm_password']) {
                    $data['confirm_password_err'] = "Password do not match";
                }
            }

            //make sure errors are empty
            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) &&
                empty($data['confirm_password_err'])){

                //Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->userModel->register($data)){
                    flash('register_success', 'Your are register and can log in');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            }else{

                //load view with error
                $this->view('users/register', $data);
            }


        } else {
            $data = [
                'name' =>'',
                'email' =>'',
                'password' =>'',
                'confirm_password' =>'',
                'name_err' =>'',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' =>''

            ];

            $this->view('users/register', $data);
        }
    }

    /**
     * validate user data
     * check user by email id
     * create session of user
     */
    public function login(){
        //check for post
        if($_SERVER['REQUEST_METHOD']== 'POST'){
            //Process form

            //sanitize form data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [

                'email' =>trim($_POST['email']),
                'password' =>trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',


            ];

            //validate email
            if(empty($data['email'])){
                $data['email_err'] = "Please enter email";
            } elseif (empty($data['password'])){
                $data['password_err'] = "Please enter password";
            }


            //check for user
            if($this->userModel->findUserByEmail($data['email'])){

                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser){
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }

            } else {
                $data['email_err'] = 'No user found';
            }

            if(empty($data['email_err']) && empty($data['password_err'])){
                die('SUCCESS');
            }else {
                $this->view('users/login', $data);
            }

        } else {
            $data = [

                'email' =>'',
                'password' =>'',
                'email_err' => '',
                'password_err' => '',


            ];

            $this->view('users/login', $data);
        }
    }

    /**
     * @function showProfile
     * @param $id(int) Not Required by default 0
     * @load profile view with required fields
     *
     */

    public function showProfile($id = 0){
        if($id == 0){
            $user_id = $_SESSION['user_id'];
            $user = $this->userModel->getUSerById($user_id);
            $following = $this->userModel->getUserFollowing($user_id);
            $followers = $this->userModel->getUserFollower($user_id);

            $is_followed = '';
            $is_user_follows_me ='';

        }
        if($id >0 ){
            $user = $this->userModel->getUSerById($id);
            $following = $this->userModel->getUserFollowing((int)$id);
            $followers = $this->userModel->getUserFollower((int)$id);

            //check if user already followed by me
              if($this->userModel->checkUserAlreadyFollowed((int)$id, (int)$_SESSION['user_id'])){
                  $is_followed = true;
              } else {
                  $is_followed = false;
              }

          //check if user already follows me
            if($this->userModel->checkUserAlreadyFollowsMe((int)$id, (int)$_SESSION['user_id'])){
                $is_user_follows_me = true;
            } else {
                $is_user_follows_me = false;
            }
        }
        unset($user->password);

        $data = [
            'user'=>$user,
            'following'=> $following,
            'followers'=>$followers,
            'isFollowed'=> $is_followed,
            'isFollowsMe' => $is_user_follows_me

        ];

        $this->view('users/profile', $data);
    }

    /**
     * @param $followe_id
     * @return json encoded data
     */
    public function follow($followe_id){
        if($_SERVER['REQUEST_METHOD']== 'POST'){

            $data =[
                'follower_id' => $_SESSION['user_id'],
                'followee_id' => $followe_id
            ];

            if($this->userModel->addFollower($data)){
                $user = $this->userModel->getUSerById($followe_id);
                $following = $this->userModel->getUserFollowing((int)$followe_id);
                $followers = $this->userModel->getUserFollower((int)$followe_id);
                unset($user->password); //delete password field from array

                $data = [
                    'user'=>$user,
                    'following'=> $following,
                    'followers'=>$followers
                ];
                echo json_encode($data);
            }
        } else {
             echo "failure";
        }
    }

    /**
     * @param $followe_id
     * @return json encoded data
     */
    public function unFollow($followe_id){
        if($_SERVER['REQUEST_METHOD']== 'POST'){

            $data =[
                'follower_id' => $_SESSION['user_id'],
                'followee_id' => $followe_id
            ];

            if($this->userModel->deleteFollower($data)){
                $user = $this->userModel->getUSerById($followe_id);
                $following = $this->userModel->getUserFollowing((int)$followe_id);
                $followers = $this->userModel->getUserFollower((int)$followe_id);
                unset($user->password); //delete password field from array

                $data = [
                    'user'=>$user,
                    'following'=> $following,
                    'followers'=>$followers
                ];
                echo json_encode($data);
            }
        } else {
            echo "failure";
        }
    }

    /**
     * @param $user
     * create multiple session with different values
     */
    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;

        redirect('posts/index');

    }

    /**
     * destroy all user related session which were created in createUserSession
     * @redirect to login page
     */
    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }

}
