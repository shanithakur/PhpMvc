<?php
    class Pages extends Controller {
        public function __construct()
        {
            $this->contactModel = $this->model('Contact');
            $this->commentModel = $this->model('Comment');
        }
        public function index(){
            if(isLoggedIn()){
                redirect('posts/');
            }
            $data = ['title'=>'Share Posts'];
            $this->view('index', $data);
        }

        public function about($id = null){
            $data = [
                'title'=> 'About us'
            ];
            $this->view('about', $data);
        }

        /**
         * @loads contact us view with required data like
         * page data, comments likes and dislikes
         */
        public function contactUs(){
            $page_data = $this->contactModel->getPageContent('contactus');
            $page_comment = $this->commentModel->getCommentsDetails($page_data->id);

            foreach ($page_comment as $comment){
                $total_likes = $this->commentModel->getCommentLikesById($comment->comment_id);
                $total_dislikes = $this->commentModel->getCommentDislikesById($comment->comment_id);

                $comment->totallikes = $total_likes->likes;
                $comment->totaldislikes = $total_dislikes->dislikes;
            }

            $data = [
              'title'=>'Contact Us',
                'body'=>$page_data->body,
                'page_id'=>$page_data->id,
                'comments'=>$page_comment
            ];
            $this->view('contactUs', $data);
        }
    }
?>