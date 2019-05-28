<?php
    class Posts extends Controller{

        public function __construct()
        {
            if(!isLoggedIn()){
                redirect('users/login');
            }
            $this->postModel = $this->model('Post');
            $this->userModel = $this->model('User');
            $this->commentModel = $this->model('Comment');
        }

        /**
         * @load posts/index view with post data
         */
        public function index(){
            $total_post = $this->postModel->getCountOfPost();
            $posts = $this->postModel->getPosts();

            $data = [
                'posts'=>$posts,
                'total_post'=>$total_post
            ];
            $this->view('posts/index', $data);
        }

        /**
         * validate form data
         * add new post to db
         * @loads posts/index page
         */
        public function add(){
            if($_SERVER['REQUEST_METHOD']== 'POST'){
                //sanitize post array
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data =[
                    'title' => trim($_POST['title']),
                    'body' => preg_replace('/\s+/', ' ', trim($_POST['body'])),
                    'user_id'=> $_SESSION['user_id'],
                    'title_err'=>'',
                    'body_err' => ''
                ];

                if(empty($data['title'])){
                    $data['title_err'] = 'Please enter title';
                }

                if(empty($data['body'])){
                    $data['body_err'] = 'Please enter body text';
                }

                if(empty($data['title_err']) && empty($data['body_err'])){

                    if($this->postModel->addPost($data)){
                        flash('post_added', 'Post added');
                        redirect('posts/index');
                    }else {
                        die('something went wrong');
                    }
                }else{
                    $this->view('posts/add', $data);
                }
            } else {
                $data =[
                    'title' => '',
                    'body' =>''

                ];

                $this->view('posts/add', $data);
            }
        }


        /**
         * @param $id
         * @loads posts/show view with data containing of post, users, comments
         */
        public function show($id){
            $post = $this->postModel->getPostById($id);
            $user = $this->userModel->getUserById($post->user_id);
            $comments = $this->commentModel->getCommentsDetails($id);

            foreach ($comments as $comment){
                $total_likes = $this->commentModel->getCommentLikesById($comment->comment_id);
                $total_dislikes = $this->commentModel->getCommentDislikesById($comment->comment_id);

                $comment->totallikes = $total_likes->likes;
                $comment->totaldislikes = $total_dislikes->dislikes;
            }

            //var_dump($comments); exit();

            //formatting date of post creation time to eg 23-may-2019 03:013 PM
            $user->created_At  = date("d-M-Y h:i:A", strtotime($user->created_At));

            //formatting date of post comments to eg 23-may-2019 03:013 PM
            foreach ($comments as $comment){
                $commented_on = date("d-M-Y h:i:A", strtotime($comment->commented_on));
                $comment->commented_on = $commented_on;
            }

            $data = [
                'post'=> $post,
                'user' => $user,
                'comment'=>'',
                'comments'=> $comments
            ];
            //var_dump($data); exit();
            $this->view('posts/show', $data);
        }

        /**
         * @param $id
         * validate form data
         * update post
         * @loads posts/index view
         */
        public function edit($id){
            if($_SERVER['REQUEST_METHOD']== 'POST'){

                //sanitize post array
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data =[
                    'id'=> $id,
                    'title' => trim($_POST['title']),
                    'body' =>preg_replace('/\s+/', ' ', trim($_POST['body'])),
                    'user_id'=> $_SESSION['user_id'],
                    'title_err'=>'',
                    'body_err' => ''
                ];

                // validate title
                if(empty($data['title'])){
                    $data['title_err'] = 'Please enter title';
                }

                // validate body
                if(empty($data['body'])){
                    $data['body_err'] = 'Please enter body text';
                }

                if(empty($data['title_err']) && empty($data['body_err'])){
                    //validated
                    if($this->postModel->updatePost($data)){
                        flash('post_added', 'Post updated');
                        redirect('posts/index');
                    }else {
                        die('something went wrong');
                    }
                }else{
                    //load view with error
                    $this->view('posts/edit', $data);
                }
            } else {

                $post = $this->postModel->getPostById($id);

                //check owner
                if($post->user_id != $_SESSION['user_id']){
                    redirect('posts');
                }
                $data =[
                    'id' => $id,
                    'title' => $post->title,
                    'body' =>$post->body
                ];

                $this->view('posts/edit', $data);

            }

        }

        /**
         * @param $id
         * delete post with post id
         * redirect to posts home page
         */

        public function delete($id){
            if($_SERVER['REQUEST_METHOD']== 'POST') {

                // Get existing post from model
                $post = $this->postModel->getPostById($id);

                //check owner
                if($post->user_id != $_SESSION['user_id']){
                    redirect('posts');
                }

                if($this->postModel->deletePost($id)){
                    flash('post_added', 'Post deleted');
                    redirect('posts');

                }else{
                    die('something went wrong');
                }
            }else {
                redirect('posts');
            }
        }

    }