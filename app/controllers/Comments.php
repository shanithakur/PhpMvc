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
    public function index(){
        redirect('pages/contactus');
    }

    /**
     * @param $id $model_name
     * @loads view with required data
     * check model name based on that perform the operation
     *
     */
    public function addComment($model_id, $model_name){
        if($model_name == 'contactModel'){
            if($_SERVER['REQUEST_METHOD']== 'POST'){

                //sanitize post array
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


                $data =[

                    'model_id' => $model_id,
                    'user_id'=> $_SESSION['user_id'],
                    'comment'=> trim($_POST['contactComment']),
                    'model_name'=> $model_name,
                    'comment_err'=>'',

                ];


                // validate comment
                if(empty($data['comment'])){
                    $data['comment_err'] = 'Please enter comment';
                }
                //Check comment error exits or not
                if(empty($data['comment_err'])){

                    //validated
                    if($this->commentModel->addComment($data)){
                        redirect('pages/contactUs');
                    }else {
                        die('something went wrong');
                    }
                } else {
                    $this->view('pages/contactUs', $data);
                }
            } else {
                $data =[
                    'comment' => ''
                ];

                $this->view('posts/show/pages/contactUs', $data);
            }
        } elseif($model_name == 'postsModel'){

            if($_SERVER['REQUEST_METHOD']== 'POST'){

                //sanitize post array
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $post = $this->postModel->getPostById($model_id);
                $user = $this->userModel->getUserById($post->user_id);
                $comments = $this->commentModel->getCommentsDetails($model_id);

                foreach ($comments as $comment){
                    $total_likes = $this->commentModel->getCommentLikesById($comment->comment_id);
                    $total_dislikes = $this->commentModel->getCommentDislikesById($comment->comment_id);

                    $comment->totallikes = $total_likes->likes;
                    $comment->totaldislikes = $total_dislikes->dislikes;
                }

                $data =[
                    'post'=> $post,
                    'user' => $user,
                    'model_id' => $model_id,
                    'user_id'=> $_SESSION['user_id'],
                    'comment'=> trim($_POST['comment']),
                    'model_name'=> $model_name,
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
                        redirect('posts/show/'.$model_id);
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

                $this->view('posts/show/'.$model_id, $data);
            }
        }

    }

    /**
     * @param $id
     * @param $model_name
     * Delete comment by using comment id
     * Check model name based on that delete the record
     * Also delete the referencing like and dislike records of that comment.
     */
    public function deleteComment($id, $model_name){
        //var_dump($model_name); exit();
        if($model_name == 'contactmodel'){
            if($_SERVER['REQUEST_METHOD']== 'POST') {

                // Get existing post from model
                $post = $this->commentModel->getCommentById($id);

                //check owner
                if($post->user_id != $_SESSION['user_id']){
                    redirect('posts');
                }

                if($this->commentModel->deleteComment($id, $model_name)){
                    redirect('pages/contactUs');
                }else{
                    die('something went wrong');
                }
            }else {
                redirect('pages/contactUs');
            }
        } elseif($model_name == 'postsmodel'){
            $post_id = $_POST['post_id'];

            if($_SERVER['REQUEST_METHOD']== 'POST') {

                // Get existing post from model
                $post = $this->commentModel->getCommentById($id);

                //check owner
                if($post->user_id != $_SESSION['user_id']){
                    redirect('posts');
                }

                if($this->commentModel->deleteComment($id, $model_name)){
                    redirect('posts/show/'.$post_id);

                }else{
                    die('something went wrong');
                }
            }else {
                redirect('posts/show/'.$post_id);
            }
        } else{
            redirect('pages/index');
        }

    }


    /**
     *add new like record to db table
     * @return json encoded data
     *
     */
    public function likeComment(){
        $comment_id = $_POST['comment_id'];
        $post_id = $_POST['post_id'];

        if(!empty($_POST)){
            $data =[
              'post_id'=> $post_id,
                'comment_id'=>$comment_id,
                'user_id'=>$_SESSION['user_id'],
                'like_status'=>1
            ];

            if($this->commentModel->addNewLike($data)){
                $total_likes = $this->commentModel->getCommentLikesById($comment_id);
                $total_dislikes = $this->commentModel->getCommentDislikesById($comment_id);
               echo json_encode($data = [
                   'likes'=>$total_likes->likes,
                     'dislikes'=>$total_dislikes->dislikes
                 ]);
            }else {
                die('something went wrong');
            }
        } else{
            echo "Invalid request type";
        }
    }

    /**
     * add new dislike record to db
     * @return json encoded data
     */
    public function dislikeComment(){
        $comment_id = $_POST['comment_id'];
        $post_id = $_POST['post_id'];

        if(!empty($_POST)){
            $data =[
                'post_id'=> $post_id,
                'comment_id'=>$comment_id,
                'user_id'=>$_SESSION['user_id'],
                'like_status'=>0
            ];

            if($this->commentModel->addNewdisLike($data)){
                $total_likes = $this->commentModel->getCommentLikesById($comment_id);
                $total_dislikes = $this->commentModel->getCommentDislikesById($comment_id);
                echo json_encode($data = [
                    'likes'=>$total_likes->likes,
                    'dislikes'=>$total_dislikes->dislikes
                ]);
            }else {
                die('something went wrong');
            }
        } else{
            echo "Invalid request type";
        }
    }

}
?>