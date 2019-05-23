<?php
    class Pages extends Controller {
        public function __construct()
        {

        }
        public function index($p1 = null){
//            print_r($params);
//            print_r($p2);
//            exit();
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
    }
?>