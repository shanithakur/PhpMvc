<?php

class Comments extends Controller{

    public function __construct()
    {
        if(!isLoggedIn()){
            redirect('users/login');
        }

        $this->commentModel = $this->model('Comment');
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    /*
     *Add comment given by user on post
     *
     */
    public function addComment($id){
        if($_SERVER['REQUEST_METHOD']== 'POST'){

            //sanitize post array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $post = $this->postModel->getPostById($id);
            $user = $this->userModel->getUserById($post->user_id);
            $comments = $this->commentModel->getCommentsDetails($id);

            $data =[
                'post'=> $post,
                'user' => $user,
                'post_id' => $id,
                'user_id'=> $_SESSION['user_id'],
                'comment'=> trim($_POST['comment']),
                'comment_err'=>'',
                'comments'=> $comments
            ];


            // validate comment
            if(empty($data['comment'])){
                $data['comment_err'] = 'Please enter comment';
            }
            //Check comment error exits or not
            if(empty($data['comment_err'])){

                //validated
                if($this->commentModel->addComment($data)){
                    redirect('posts/show/'.$id);
                }else {
                    die('something went wrong');
                }
            }else{
                $this->view('posts/show', $data);
            }
        } else {
            $data =[
                'comment' => ''
            ];

            $this->view('posts/show/'.$id, $data);
        }
    }

    /*
     * Delete comment by using comment id
     */
    public function deleteComment($id){
        $post_id = $_POST['post_id'];

        if($_SERVER['REQUEST_METHOD']== 'POST') {

            // Get existing post from model
            $post = $this->commentModel->getCommentById($id);
            //var_dump($post); exit();
            //check owner
            if($post->user_id != $_SESSION['user_id']){
                redirect('posts');
            }

            if($this->commentModel->deleteComment($id)){
                //flash('post_added', 'Post deleted');
                redirect('posts/show/'.$post_id);

            }else{
                die('something went wrong');
            }
        }else {
            redirect('posts/show/'.$post_id);
        }
    }

}
?>